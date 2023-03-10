<?php

namespace App\Notifications;

use Spatie\Backup\Notifications\Notifications\BackupWasSuccessful as ParentBackupWasSuccessful;
use Illuminate\Support\Collection;
use Spatie\Backup\Helpers\Format;

class BackupWasSuccessful extends ParentBackupWasSuccessful
{
    protected function backupDestinationProperties(): Collection
    {
        $backupDestination = $this->backupDestination();

        if (! $backupDestination) {
            return collect();
        }

        $backupDestination->fresh();

        $newestBackup = $backupDestination->newestBackup();
        $oldestBackup = $backupDestination->oldestBackup();

        return collect([
            'Application name' => $this->applicationName(),
            'Backup name' => $this->backupName(),
            'Disk' => $backupDestination->diskName(),
            'Newest backup size' => $newestBackup ? Format::humanReadableSize($newestBackup->size()) : 'No backups were made yet',
            'Amount of backups' => (string) $backupDestination->backups()->count(),
            'Total storage used' => Format::humanReadableSize($backupDestination->backups()->size()),
            'Newest backup date' => $newestBackup ? $newestBackup->date()->format('Y/m/d H:i:s') : 'No backups were made yet',
            'Oldest backup date' => $oldestBackup ? $oldestBackup->date()->format('Y/m/d H:i:s') : 'No backups were made yet',
            'Download Url' => url('backup_download').'?path='. $newestBackup->path(),
        ])->filter();
    }
}
