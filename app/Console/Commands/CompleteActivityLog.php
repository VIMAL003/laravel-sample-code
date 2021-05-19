<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Admin;
use App\Models\Staff;
use App\Models\ActivityLog;
use DataTables;
use Illuminate\Console\Command;

class CompleteActivityLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'activity:end';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'End activity by adding 8 hours to start time';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $activities = array();

      $activities = ActivityLog::where('end_time', '=', '')
      ->orWhereNull('end_time')
      ->get()->toArray();

      if( count( $activities ) > 0 ) {
        foreach ($activities as $key => $value) {
          $act_log = ActivityLog::find( $value['id'] );
          $act_log->end_time = date( 'H:i:s', strtotime( '+8 hour', strtotime( $value['start_time'] ) ) );
          $act_log->save();
        }
      }
    }
  }
