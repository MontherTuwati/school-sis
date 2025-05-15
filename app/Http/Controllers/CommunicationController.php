<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Models\Announcement;
use App\Models\Notification;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;

class CommunicationController extends Controller
{
    /** communication dashboard */
    public function index()
    {
        $stats = $this->getCommunicationStats();
        $recentMessages = Message::with(['sender', 'recipient'])
                               ->orderBy('created_at', 'desc')
                               ->limit(10)
                               ->get();
        $recentAnnouncements = Announcement::with('createdBy')
                                         ->orderBy('created_at', 'desc')
                                         ->limit(5)
                                         ->get();
        
        return view('communication.index', compact('stats', 'recentMessages', 'recentAnnouncements'));
    }

    /** messages management */
    public function messages()
    {
        $messages = Message::with(['sender', 'recipient'])
                         ->where('sender_id', auth()->id())
                         ->orWhere('recipient_id', auth()->id())
                         ->orderBy('created_at', 'desc')
                         ->get();
        
        $students = Student::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $users = User::where('is_active', true)->get();
        
        return view('communication.messages', compact('messages', 'students', 'teachers', 'users'));
    }

    /** compose message */
    public function compose()
    {
        $students = Student::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $users = User::where('is_active', true)->get();
        
        return view('communication.compose', compact('students', 'teachers', 'users'));
    }

    /** send message */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:student,teacher,user',
            'recipient_id' => 'required|integer',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'priority' => 'required|in:low,normal,high,urgent',
            'is_private' => 'boolean',
        ]);

        try {
            $message = new Message();
            $message->sender_id = auth()->id();
            $message->recipient_type = $request->recipient_type;
            $message->recipient_id = $request->recipient_id;
            $message->subject = $request->subject;
            $message->message = $request->message;
            $message->priority = $request->priority;
            $message->is_private = $request->boolean('is_private');
            $message->status = 'sent';
            $message->save();
            
            // Create notification for recipient
            $this->createNotification($request->recipient_type, $request->recipient_id, 'message', $message->id);
            
            return redirect()->route('communication.messages')->with('success', 'Message sent successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to send message: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send message. Please try again.');
        }
    }

    /** view message */
    public function viewMessage($id)
    {
        $message = Message::with(['sender', 'recipient'])->findOrFail($id);
        
        // Mark as read if recipient is viewing
        if ($message->recipient_id == auth()->id() && $message->status == 'sent') {
            $message->status = 'read';
            $message->read_at = Carbon::now();
            $message->save();
        }
        
        return view('communication.view-message', compact('message'));
    }

    /** reply to message */
    public function reply(Request $request, $id)
    {
        $originalMessage = Message::findOrFail($id);
        
        $request->validate([
            'message' => 'required|string|max:2000',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        try {
            $reply = new Message();
            $reply->sender_id = auth()->id();
            $reply->recipient_type = $originalMessage->sender_type ?? 'user';
            $reply->recipient_id = $originalMessage->sender_id;
            $reply->subject = 'Re: ' . $originalMessage->subject;
            $reply->message = $request->message;
            $reply->priority = $request->priority;
            $reply->parent_id = $id;
            $reply->status = 'sent';
            $reply->save();
            
            // Create notification for recipient
            $this->createNotification($reply->recipient_type, $reply->recipient_id, 'message', $reply->id);
            
            return redirect()->route('communication.view-message', $id)->with('success', 'Reply sent successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to send reply: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to send reply. Please try again.');
        }
    }

    /** delete message */
    public function deleteMessage($id)
    {
        try {
            $message = Message::findOrFail($id);
            
            // Only sender or recipient can delete
            if ($message->sender_id != auth()->id() && $message->recipient_id != auth()->id()) {
                return redirect()->back()->with('error', 'You are not authorized to delete this message.');
            }
            
            $message->delete();
            
            return redirect()->route('communication.messages')->with('success', 'Message deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete message: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete message. Please try again.');
        }
    }

    /** announcements management */
    public function announcements()
    {
        $announcements = Announcement::with('createdBy')->orderBy('created_at', 'desc')->get();
        
        return view('communication.announcements', compact('announcements'));
    }

    /** create announcement */
    public function createAnnouncement()
    {
        return view('communication.create-announcement');
    }

    /** store announcement */
    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'type' => 'required|in:general,academic,event,emergency',
            'target_audience' => 'required|in:all,students,teachers,parents,staff',
            'is_published' => 'boolean',
            'publish_date' => 'required|date|after_or_equal:today',
            'expiry_date' => 'nullable|date|after:publish_date',
        ]);

        try {
            $announcement = new Announcement();
            $announcement->title = $request->title;
            $announcement->content = $request->content;
            $announcement->type = $request->type;
            $announcement->target_audience = $request->target_audience;
            $announcement->is_published = $request->boolean('is_published');
            $announcement->publish_date = $request->publish_date;
            $announcement->expiry_date = $request->expiry_date;
            $announcement->created_by = auth()->id();
            $announcement->save();
            
            // Create notifications for target audience
            if ($announcement->is_published) {
                $this->createAnnouncementNotifications($announcement);
            }
            
            return redirect()->route('communication.announcements')->with('success', 'Announcement created successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to create announcement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create announcement. Please try again.');
        }
    }

    /** edit announcement */
    public function editAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        return view('communication.edit-announcement', compact('announcement'));
    }

    /** update announcement */
    public function updateAnnouncement(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
            'type' => 'required|in:general,academic,event,emergency',
            'target_audience' => 'required|in:all,students,teachers,parents,staff',
            'is_published' => 'boolean',
            'publish_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:publish_date',
        ]);

        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->title = $request->title;
            $announcement->content = $request->content;
            $announcement->type = $request->type;
            $announcement->target_audience = $request->target_audience;
            $announcement->is_published = $request->boolean('is_published');
            $announcement->publish_date = $request->publish_date;
            $announcement->expiry_date = $request->expiry_date;
            $announcement->save();
            
            return redirect()->route('communication.announcements')->with('success', 'Announcement updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update announcement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update announcement. Please try again.');
        }
    }

    /** delete announcement */
    public function deleteAnnouncement($id)
    {
        try {
            $announcement = Announcement::findOrFail($id);
            $announcement->delete();
            
            return redirect()->route('communication.announcements')->with('success', 'Announcement deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete announcement: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete announcement. Please try again.');
        }
    }

    /** notifications management */
    public function notifications()
    {
        $notifications = Notification::with(['recipient'])
                                   ->where('recipient_id', auth()->id())
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        
        return view('communication.notifications', compact('notifications'));
    }

    /** mark notification as read */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('recipient_id', auth()->id())
                                      ->findOrFail($id);
            $notification->is_read = true;
            $notification->read_at = Carbon::now();
            $notification->save();
            
            return response()->json(['success' => true]);
            
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /** mark all notifications as read */
    public function markAllAsRead()
    {
        try {
            Notification::where('recipient_id', auth()->id())
                       ->where('is_read', false)
                       ->update([
                           'is_read' => true,
                           'read_at' => Carbon::now()
                       ]);
            
            return redirect()->route('communication.notifications')->with('success', 'All notifications marked as read!');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to mark notifications as read.');
        }
    }

    /** communication reports */
    public function reports()
    {
        $stats = $this->getCommunicationReportStats();
        
        return view('communication.reports', compact('stats'));
    }

    /** export communication data */
    public function export(Request $request)
    {
        $type = $request->input('type', 'messages');
        $format = $request->input('format', 'csv');
        
        switch ($type) {
            case 'messages':
                $data = Message::with(['sender', 'recipient'])->get();
                break;
            case 'announcements':
                $data = Announcement::with('createdBy')->get();
                break;
            case 'notifications':
                $data = Notification::with('recipient')->get();
                break;
            default:
                $data = [];
        }
        
        if ($format === 'csv') {
            return $this->exportToCSV($data, $type);
        } else {
            return $this->exportToPDF($data, $type);
        }
    }

    /** get communication statistics */
    private function getCommunicationStats()
    {
        $totalMessages = Message::count();
        $unreadMessages = Message::where('recipient_id', auth()->id())
                               ->where('status', 'sent')
                               ->count();
        
        $totalAnnouncements = Announcement::count();
        $publishedAnnouncements = Announcement::where('is_published', true)->count();
        
        $totalNotifications = Notification::count();
        $unreadNotifications = Notification::where('recipient_id', auth()->id())
                                         ->where('is_read', false)
                                         ->count();
        
        // Recent activity
        $recentMessages = Message::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        $recentAnnouncements = Announcement::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        return [
            'total_messages' => $totalMessages,
            'unread_messages' => $unreadMessages,
            'total_announcements' => $totalAnnouncements,
            'published_announcements' => $publishedAnnouncements,
            'total_notifications' => $totalNotifications,
            'unread_notifications' => $unreadNotifications,
            'recent_messages' => $recentMessages,
            'recent_announcements' => $recentAnnouncements,
        ];
    }

    /** get communication report statistics */
    private function getCommunicationReportStats()
    {
        // Message statistics by priority
        $messagePriorities = Message::selectRaw('priority, COUNT(*) as count')
                                  ->groupBy('priority')
                                  ->get();
        
        // Message statistics by month
        $monthlyMessages = [];
        for ($i = 0; $i < 6; $i++) {
            $month = Carbon::now()->subMonths($i);
            $count = Message::whereYear('created_at', $month->year)
                          ->whereMonth('created_at', $month->month)
                          ->count();
            $monthlyMessages[$month->format('M Y')] = $count;
        }
        
        // Announcement statistics by type
        $announcementTypes = Announcement::selectRaw('type, COUNT(*) as count')
                                       ->groupBy('type')
                                       ->get();
        
        // Notification statistics
        $notificationStats = Notification::selectRaw('type, COUNT(*) as count, SUM(CASE WHEN is_read = 1 THEN 1 ELSE 0 END) as read_count')
                                       ->groupBy('type')
                                       ->get();
        
        return [
            'message_priorities' => $messagePriorities,
            'monthly_messages' => $monthlyMessages,
            'announcement_types' => $announcementTypes,
            'notification_stats' => $notificationStats,
        ];
    }

    /** create notification */
    private function createNotification($recipientType, $recipientId, $type, $referenceId)
    {
        try {
            $notification = new Notification();
            $notification->recipient_type = $recipientType;
            $notification->recipient_id = $recipientId;
            $notification->type = $type;
            $notification->reference_id = $referenceId;
            $notification->is_read = false;
            $notification->save();
        } catch (\Exception $e) {
            \Log::error('Failed to create notification: ' . $e->getMessage());
        }
    }

    /** create announcement notifications */
    private function createAnnouncementNotifications($announcement)
    {
        try {
            switch ($announcement->target_audience) {
                case 'all':
                    $recipients = User::where('is_active', true)->get();
                    break;
                case 'students':
                    $recipients = Student::where('is_active', true)->get();
                    break;
                case 'teachers':
                    $recipients = Teacher::where('is_active', true)->get();
                    break;
                default:
                    $recipients = collect();
            }
            
            foreach ($recipients as $recipient) {
                $this->createNotification(
                    $announcement->target_audience === 'all' ? 'user' : $announcement->target_audience,
                    $recipient->id,
                    'announcement',
                    $announcement->id
                );
            }
        } catch (\Exception $e) {
            \Log::error('Failed to create announcement notifications: ' . $e->getMessage());
        }
    }

    /** export to CSV */
    private function exportToCSV($data, $type)
    {
        $filename = $type . '_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'messages':
                    fputcsv($file, ['Sender', 'Recipient', 'Subject', 'Priority', 'Status', 'Date']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->sender ? $item->sender->name : 'N/A',
                            $item->recipient ? $item->recipient->name : 'N/A',
                            $item->subject,
                            ucfirst($item->priority),
                            ucfirst($item->status),
                            $item->created_at
                        ]);
                    }
                    break;
                case 'announcements':
                    fputcsv($file, ['Title', 'Type', 'Target Audience', 'Published', 'Publish Date', 'Created By']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->title,
                            ucfirst($item->type),
                            ucfirst($item->target_audience),
                            $item->is_published ? 'Yes' : 'No',
                            $item->publish_date,
                            $item->createdBy ? $item->createdBy->name : 'N/A'
                        ]);
                    }
                    break;
                case 'notifications':
                    fputcsv($file, ['Recipient', 'Type', 'Read', 'Created Date']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->recipient ? $item->recipient->name : 'N/A',
                            ucfirst($item->type),
                            $item->is_read ? 'Yes' : 'No',
                            $item->created_at
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /** export to PDF */
    private function exportToPDF($data, $type)
    {
        // Placeholder for PDF export - would use a library like DomPDF
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}
