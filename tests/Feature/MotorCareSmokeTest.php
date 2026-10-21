<?php
namespace Tests\Feature;
use App\Models\ServicePackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class MotorCareSmokeTest extends TestCase {
    use RefreshDatabase;
    public function test_home_and_catalog_are_public(): void {
        ServicePackage::create(['name'=>'Paket Uji','slug'=>'paket-uji','description'=>'Paket pengujian','price'=>100000,'service_type'=>'both','features'=>[],'is_active'=>true]);
        $this->get(route('home'))->assertOk()->assertSee('MotorCare');
        $this->get(route('services.index'))->assertOk()->assertSee('Paket Uji');
    }
    public function test_checkout_requires_login(): void { $this->get(route('checkout'))->assertRedirect(route('login')); }
    public function test_admin_dashboard_is_restricted_by_role(): void {
        $customer = User::factory()->create(['role'=>'customer']);
        $this->actingAs($customer)->get(route('admin.dashboard'))->assertForbidden();
        $admin = User::factory()->create(['role'=>'admin']);
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();
    }
}
