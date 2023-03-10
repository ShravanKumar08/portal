<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;

class InterviewCallNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($schedule)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $url = url('/admin/interviewcall/'.$notifiable->interviewcall_id);
        $candidate = $notifiable->interview_call->candidate;

        return (new SlackMessage)
            ->success()
            ->content('Interviewcall Schedule Notification!')
            ->attachment(function ($attachment) use ($url, $candidate, $notifiable) {
                $dateTime = Carbon::parse($notifiable->datetime)->format('d-m-Y h:i A');
                $attachment->title('Go to interviewcall page', $url)
                ->fields([
                        'Name'  =>  $candidate->name,
                        'Email' =>  $candidate->email,
                        'Mobile'=>  $candidate->mobile,
                        'Schedule Time' => $dateTime,
                    ]);
                });
               
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
