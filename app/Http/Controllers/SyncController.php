<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Task;
use App\Models\Note;
use App\Models\Proposal;
use App\Models\Expense;
use App\Models\Subscription;
use App\Models\Lead;
use App\Models\EmailThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Sync data between Flutter app and server.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sync(Request $request)
    {
        $request->validate([
            'last_sync_timestamp' => 'nullable|date',
            'clients' => 'array',
            'projects' => 'array',
            'tasks' => 'array',
            'invoices' => 'array',
            'notes' => 'array',
            'proposals' => 'array',
            'expenses' => 'array',
            'subscriptions' => 'array',
            'leads' => 'array',
            'email_threads' => 'array',
        ]);

        $userId = auth()->id();
        $lastSyncTimestamp = $request->input('last_sync_timestamp');

        // Log Daily Active User (DAU)
        \App\Models\UserActivity::updateOrCreate([
            'user_id' => $userId,
            'date' => now()->toDateString(),
        ], [
            'action' => 'sync',
            'updated_at' => now(),
        ]);

        DB::beginTransaction();

        try {
            // Process incoming clients
            foreach ($request->input('clients', []) as $data) {
                $syncData = collect($data)->only(['name', 'email', 'phone', 'company', 'notes', 'updated_at'])->toArray();
                $client = Client::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $client->delete();
            }

            // Process incoming projects
            foreach ($request->input('projects', []) as $data) {
                $syncData = collect($data)->only(['client_id', 'name', 'client_name', 'budget', 'status', 'deadline', 'estimated_hours', 'currency', 'updated_at'])->toArray();
                $project = Project::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $project->delete();
            }

            // Process incoming tasks
            foreach ($request->input('tasks', []) as $data) {
                $syncData = collect($data)->only(['project_id', 'title', 'is_completed', 'total_seconds', 'is_running', 'last_start_time', 'daily_tracked', 'status', 'updated_at'])->toArray();
                if (isset($syncData['daily_tracked']) && is_string($syncData['daily_tracked'])) {
                    $syncData['daily_tracked'] = json_decode($syncData['daily_tracked'], true);
                }
                $task = Task::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $task->delete();
            }

            // Process incoming invoices
            foreach ($request->input('invoices', []) as $data) {
                $syncData = collect($data)->only(['client_id', 'project_id', 'client_name', 'amount', 'date', 'due_date', 'status', 'is_external', 'currency', 'is_gst_enabled', 'gst_percentage', 'description', 'updated_at'])->toArray();
                $invoice = Invoice::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $invoice->delete();
            }

            // Process incoming notes
            foreach ($request->input('notes', []) as $data) {
                $syncData = collect($data)->only(['content', 'color_index', 'updated_at'])->toArray();
                $note = Note::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $note->delete();
            }

            // Process incoming proposals
            foreach ($request->input('proposals', []) as $data) {
                $syncData = collect($data)->only(['client_name', 'project_title', 'description', 'estimated_budget', 'date_sent', 'status', 'timeline', 'style', 'updated_at'])->toArray();
                $proposal = Proposal::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $proposal->delete();
            }

            // Process incoming expenses
            foreach ($request->input('expenses', []) as $data) {
                $syncData = collect($data)->only(['project_id', 'description', 'amount', 'date', 'category', 'receipt_path', 'currency', 'updated_at'])->toArray();
                $expense = Expense::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $expense->delete();
            }

            // Process incoming subscriptions
            foreach ($request->input('subscriptions', []) as $data) {
                $syncData = collect($data)->only(['name', 'amount', 'currency', 'billing_cycle', 'next_billing_date', 'category', 'is_active', 'notes', 'updated_at'])->toArray();
                $subscription = Subscription::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $subscription->delete();
            }

            // Process incoming leads
            foreach ($request->input('leads', []) as $data) {
                $syncData = collect($data)->only(['name', 'email', 'phone', 'company', 'source', 'status', 'estimated_value', 'notes', 'last_contact_date', 'updated_at'])->toArray();
                $lead = Lead::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $lead->delete();
            }

            // Process incoming email threads
            foreach ($request->input('email_threads', []) as $data) {
                $syncData = collect($data)->only(['subject', 'from_email', 'from_name', 'to_email', 'client_id', 'project_id', 'invoice_id', 'snippet', 'received_at', 'is_read', 'is_important', 'labels', 'updated_at'])->toArray();
                if (isset($syncData['labels']) && is_string($syncData['labels'])) {
                    $syncData['labels'] = json_decode($syncData['labels'], true);
                }
                $emailThread = EmailThread::withTrashed()->updateOrCreate(['id' => $data['id']], array_merge($syncData, ['user_id' => $userId]));
                if (!empty($data['is_deleted'])) $emailThread->delete();
            }

            DB::commit();

            // Fetch changes from server since last sync
            $serverChanges = $this->getServerChanges($userId, $lastSyncTimestamp);

            return response()->json([
                'message' => 'Sync completed successfully',
                'changes' => $serverChanges, // Changed 'data' to 'changes' to match Flutter code
                'server_time' => now()->toIso8601String(), // Changed 'sync_timestamp' to 'server_time' to match Flutter code
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Sync Error: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $userId,
                'payload' => $request->all()
            ]);

            return response()->json([
                'message' => 'Sync failed',
                'error' => $e->getMessage(), // Sending full error for debugging
                'trace' => config('app.debug') ? $e->getTrace() : null
            ], 500);
        }
    }

    /**
     * Get all records that changed after the last sync timestamp.
     *
     * @param  string  $userId
     * @param  string|null  $lastSyncTimestamp
     * @return array
     */
    private function getServerChanges($userId, $lastSyncTimestamp)
    {
        $query = function ($model) use ($userId, $lastSyncTimestamp) {
            $query = $model::withTrashed()->where('user_id', $userId);
            
            if ($lastSyncTimestamp && $lastSyncTimestamp !== '1970-01-01T00:00:00Z') {
                $query->where(function ($q) use ($lastSyncTimestamp) {
                    $q->where('updated_at', '>', $lastSyncTimestamp)
                      ->orWhere('deleted_at', '>', $lastSyncTimestamp);
                });
            }
            
            return $query->get();
        };

        return [
            'clients' => $query(new Client()),
            'projects' => $query(new Project()),
            'tasks' => $query(new Task()),
            'invoices' => $query(new Invoice()),
            'notes' => $query(new Note()),
            'proposals' => $query(new Proposal()),
            'expenses' => $query(new Expense()),
            'subscriptions' => $query(new Subscription()),
            'leads' => $query(new Lead()),
            'email_threads' => $query(new EmailThread()),
        ];
    }
}
