<?php

namespace Asobiba\Domain\Models\Reservation;

class Plan
{
	private $plan;

	private $price;

	private $capacity;

	private $priceSet = [

		'【非商用】基本プラン(平日)' => 19500,
		'【非商用】基本プラン(休日)' => 20500,
		'【非商用】お昼5時間パック' => 17000,
		'【非商用】夜5時間パック' => 18000,
		'【商用】基本１日プラン' => 28500,		
		'【商用】お昼5時間パック' => 24000,
		'【商用】夜5時間パック' => 25000,
		'【商用】3時間パック' => 20000,
		'【商用】2時間パック' => 17000,
	];

	private $startTimeSet = [

		'【非商用】基本プラン(平日)' => 11,
		'【非商用】基本プラン(休日)' => 11,
		'【非商用】お昼5時間パック' => 11,
		'【非商用】夜5時間パック' => 17,
		'【商用】基本１日プラン' => 11,		
		'【商用】お昼5時間パック' => 11,
		'【商用】夜5時間パック' => 17,
		'【商用】3時間パック' => 11,
		'【商用】2時間パック' => 11,
	];

	private $endTimeSet = [

		'【非商用】基本プラン(平日)' => 22,
		'【非商用】基本プラン(休日)' => 22,
		'【非商用】お昼5時間パック' => 16,
		'【非商用】夜5時間パック' => 22,
		'【商用】基本１日プラン' => 22,		
		'【商用】お昼5時間パック' => 16,
		'【商用】夜5時間パック' => 22,
		'【商用】3時間パック' => 22,
		'【商用】2時間パック' => 22,
	];

	public function __construct(String $plan,Options $options)
	{
		$this->plan = $plan;
		$this->price = $this->setBasePrice($plan);
		$this->capacity = $this->setCapacity($plan,$options);
	}

	//Priceクラスに分割
	private function setBasePrice(String $plan): int
	{
		return (int)$this->priceSet[$plan];
	}

	public function getBasePrice(): int
	{
		return (int)$this->price;
	}

	//Capacityクラスに分割
	private function setCapacity(String $plan,Options $options): int
	{
		$planCategoryCheck = strpos($plan,'非');
		if(!$planCategoryCheck){
			return 15;
		}
		if($options->hasLargeGroupOption()){
			return 15;
		}
		return 11;
	}

	public function getCapacity(): int
	{
		return $this->capacity;
	}

	//AcceptableTimeクラスに分割？
	public function getAcceptableStartTime():int
	{
		return $this->startTimeSet[$this->plan];
	}

	public function getAcceptableEndTime():int
	{
		return $this->endTimeSet[$this->plan];
	}

	public function hasShortTimePlan():bool
	{
		return strpos($this->plan,'2時間') || strpos($this->plan,'3時間');
	}

	public function getAcceptableUtilizationTime():int
	{
		return (int)$this->endTimeSet[$this->plan] - $this->startTimeSet[$this->plan];
	}
}


?>