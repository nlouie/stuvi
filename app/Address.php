<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use League\Flysystem\Exception;

/**
 * Created by PhpStorm.
 * User: Tianyou Luo
 * Date: 6/4/15
 * Time: 10:57 AM
 */


class Address extends Model
{

    protected $table = 'addresses';
    protected $guarded = [];
    protected $dates = ['deleted_at'];

    /*
	|--------------------------------------------------------------------------
	| Relationships
	|--------------------------------------------------------------------------
	*/

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /*
	|--------------------------------------------------------------------------
	| Methods
	|--------------------------------------------------------------------------
	*/

    public function belongsToUser($user_id)
    {
        return $this->user_id == $user_id;
    }

    /**
     * Set this address as default.
     */
    public function setDefault()
    {
        $stored_addresses = Address::where('user_id', $this->user_id)->get();

        foreach ($stored_addresses as $user_address) {
            if ($user_address->is_default == true && $user_address->id != $this->id) {
                $user_address->update([
                    "is_default" => false
                ]);
            }
        }

        $this->update(["is_default" => true]);
    }

    /**
     * Disable this address.
     */
    public function disable()
    {
        $this->update([
            'is_default' => false,
            'is_enabled' => false
        ]);
    }

    /**
     * Get the rules of addressee, street, city, state, zip
     *
     * @return array
     */
    public static function rules() {
        $rules = array(
            'addressee'     => 'required|string|Max:100',
            'address_line1' => 'required|string|Max:100',
            'address_line2' => 'string|Max:100',
            'city'          => 'required|string',
            'state_a2'      => 'required|Alpha|size:2',
            'zip'           => 'required|AlphaDash|Min:5|Max:10', // https://www.barnesandnoble.com/help/cds2.asp?PID=8134
            'phone_number'  => 'required|string'
        );

        if(config('addresses::show_country')) {
            $rules['country_a2'] = 'required|Alpha|size:2';
        }

        return $rules;
    }
}