<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [

		/*
		|--------------------------------------------------------------------------
		| User events
		|--------------------------------------------------------------------------
		*/

		'App\Events\UserWasSignedUp' => [
			'App\Listeners\EmailSignedUpConfirmationToUser',
		],

		'App\Events\UserEmailWasAdded' => [
			'App\Listeners\EmailNewEmailAddedConfirmationToUser',
		],

		'App\Events\UserPasswordWasChanged' => [
			'App\Listeners\EmailPasswordChangedNotificationToUser',
		],

		/*
		|--------------------------------------------------------------------------
		| Product events
		|--------------------------------------------------------------------------
		*/

		// book reminder
		'App\Events\ProductWasCreated' => [
			'App\Listeners\EmailBookAvailableNotificationToUsers',
			'App\Listeners\MessageProductCreatedNotificationToStuvi'
		],

		// product that sells to user
		'App\Events\ProductIsAvailableSoon' => [
			'App\Listeners\EmailProductAvailableSoonNotificationToSeller',
		],

		// product that can trade in to Stuvi
		'App\Events\ProductWasUpdatedPriceAndApproved' => [
			'App\Listeners\EmailProductUpdatedPriceAndApprovedNotificationToSeller',
		],

		'App\Events\ProductWasRejected' => [
			'App\Listeners\EmailProductRejectedNotificationToSeller',
		],

		'App\Events\ProductHasInvalidPhoto'	=> [
			'App\Listeners\EmailProductHasInvalidPhotoToSeller',
		],

		/*
		|--------------------------------------------------------------------------
		| Buyer order events
		|--------------------------------------------------------------------------
		*/

		'App\Events\BuyerOrderWasPlaced' => [
			'App\Listeners\EmailBuyerOrderConfirmationToBuyer',
//			'App\Listeners\MessageBuyerOrderPlacedNotificationToStuvi',
		],

		// when all seller orders are picked up
		'App\Events\BuyerOrderWasDeliverable' => [
			'App\Listeners\EmailBuyerOrderDeliverableNotificationToBuyer',
			'App\Listeners\MessageBuyerOrderDeliverableNotificationToBuyer',
		],

		'App\Events\BuyerOrderDeliveryWasScheduled' => [
			'App\Listeners\EmailBuyerOrderDeliveryScheduledNotificationToStuvi',
			'App\Listeners\MessageBuyerOrderDeliveryScheduledNotificationToStuvi',
		],

		'App\Events\BuyerOrderWasShipped' => [
			'App\Listeners\EmailBuyerOrderShippedNotificationToBuyer',
			'App\Listeners\MessageBuyerOrderShippedNotificationToBuyer',
		],

		'App\Events\BuyerOrderWasDelivered' => [
			'App\Listeners\EmailBuyerOrderDeliveredNotificationToBuyer',
			'App\Listeners\CapturePaypalAuthorizedPaymentFromBuyer',
			'App\Listeners\CreatePaypalPayoutToSellers',
			'App\Listeners\EmailBuyerOrderDeliveredNotificationToSeller',
		],

        'App\Events\BuyerOrderWasCancelled' => [
            'App\Listeners\EmailBuyerOrderCancelledNotificationToBuyer',
			'App\Listeners\VoidPaypalAuthorizedPaymentOfBuyerOrder',
        ],

		/*
		|--------------------------------------------------------------------------
		| Seller order events
		|--------------------------------------------------------------------------
		*/

		'App\Events\SellerOrderWasCreated' => [
			'App\Listeners\EmailSellerOrderConfirmationToSeller',
			'App\Listeners\MessageSellerOrderConfirmationToSeller',
		],

		'App\Events\SellerOrderPickupWasScheduled' => [
//			'App\Listeners\EmailSellerOrderPickupScheduledConfirmationToSeller',
			'App\Listeners\EmailSellerOrderPickupScheduledNotificationToStuvi',
			'App\Listeners\MessageSellerOrderPickupScheduledNotificationToStuvi',
		],

		'App\Events\SellerOrderWasAssignedToCourier' => [
			'App\Listeners\EmailSellerOrderReadyToPickupNotificationToSeller',
			'App\Listeners\MessageSellerOrderReadyToPickupNotificationToSeller',
		],

		'App\Events\SellerOrderWasPickedUp' => [
			'App\Listeners\EmailSellerOrderPickedupNotificationToSeller',
		],

		'App\Events\SellerOrderWasCancelled' => [
			'App\Listeners\MessageSellerOrderCancelledToCourier',
			'App\Listeners\EmailSellerOrderCancelledToBuyer',
            'App\Listeners\EmailSellerOrderCancelledToSeller',
		],

		/*
		|--------------------------------------------------------------------------
		| Donation events
		|--------------------------------------------------------------------------
		*/

		'App\Events\DonationWasCreated' => [
			'App\Listeners\EmailDonationPickupNotificationToStuvi',
		],

		'App\Events\DonationWasAssignedToCourier' => [
			'App\Listeners\EmailDonationReadyToPickupNotificationToDonator',
		],

		/*
		|--------------------------------------------------------------------------
		| Contact events
		|--------------------------------------------------------------------------
		*/

		'App\Events\ContactMessageWasCreated' => [
			'App\Listeners\EmailContactMessageToStaff',
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

		//
	}

}
