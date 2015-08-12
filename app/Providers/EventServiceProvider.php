<?php namespace App\Providers;

use App\Branch;
use App\Province;
use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

        Province::creating(function($model)
        {
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifieddate = date("Y-m-d H:i:s");
        });
        Province::updating(function($model)
        {
            $model->modifieddate = date("Y-m-d H:i:s");
        });

        Branch::creating(function($model)
        {
            $model->createddate = date("Y-m-d H:i:s");
            $model->modifieddate = date("Y-m-d H:i:s");
        });
        Branch::updating(function($model)
        {
            $model->modifieddate = date("Y-m-d H:i:s");
        });
	}

}
