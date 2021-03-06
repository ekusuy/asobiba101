<?php

namespace Tests\Feature\Reservation;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Asobiba\Domain\Models\Reservation\Reservation;
use Asobiba\Infrastructure\Repositories\EloquentReservationRepository;
use DB;

class GetValueTest extends TestCase
{

    use RefreshDatabase;

    public function repository()
    {
        DB::table('reservation_seqs')->insert(["nextval" => 0]);
        return new EloquentReservationRepository;
    }

    /**
     *
     * Get plan name.
     *
     */
    public function testGetPlanName()
    {
        $request = makeCorrectRequest();

        $id = $this->repository()->nextIdentity();
        $reservation = createReservation($id,$request);
        $this->assertEquals('【非商用】基本プラン(平日)',$reservation->getPlanName());
    }

    /**
     * Get date & time.
     */
    public function testGetDateTime()
    {
        $request = makeCorrectRequest();

        $id = $this->repository()->nextIdentity();
        $reservation = createReservation($id,$request);

        $this->assertEquals('2017-11-26',$reservation->getDate());
        $this->assertEquals(11,$reservation->getStartTime());
        $this->assertEquals(9,$reservation->getEndTime());

        array_splice($request->options, 2, 1);//宿泊オプションを削除
        $id = $this->repository()->nextIdentity();
        $reservation = createReservation($id,$request);

        $this->assertEquals(22,$reservation->getEndTime());

        $request->options[2] = '深夜利用';//宿泊オプションを深夜利用に変更
        $id = $this->repository()->nextIdentity();
        $reservation = createReservation($id,$request);

        $this->assertEquals(24,$reservation->getEndTime());

    }

    /**
     * Get status of this reservation.
     */
    public function testChangeStatus()
    {
        $request = makeCorrectRequest();

        $id = $this->repository()->nextIdentity();
        $reservation = createReservation($id,$request);

        $this->assertEquals('Contact',$reservation->getStatus());
        $reservation->changeStatus('Confirmation');
        $this->assertEquals('Confirmation',$reservation->getStatus());
        $reservation->changeStatus('BeforePayment');
        $this->assertEquals('BeforePayment',$reservation->getStatus());
        $reservation->changeStatus('AfterPayment');
        $this->assertEquals('AfterPayment',$reservation->getStatus());
        $reservation->changeStatus('BeforeUse');
        $this->assertEquals('BeforeUse',$reservation->getStatus());
        $reservation->changeStatus('Used');
        $this->assertEquals('Used',$reservation->getStatus());
        $reservation->changeStatus('Canceled');
        $this->assertEquals('Canceled',$reservation->getStatus());

        try {
            $reservation->changeStatus('Confirmation');
            $this->fail('例外無し');
        }catch(\InvalidArgumentException $e){
            $this->assertEquals('このステータスには変更出来ません',$e->getMessage());
        }

    }
    public function testGetNumber()
    {
        $request = makeCorrectRequest();

        $id = $this->repository()->nextIdentity();
        $reservation = createReservation($id,$request);

        $this->assertEquals(10,$reservation->getNumber());

    }

}
