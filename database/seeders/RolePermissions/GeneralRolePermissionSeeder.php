<?php

namespace Database\Seeders\RolePermissions;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GeneralRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * alttaki kodda tablo sifirlandiginda tablonun iliskileri bunu engelliyordu.
         * bu yuzden bu seeder calistirildiginda iliskileri engelliyoruz.
         * en altta tekrardan aktif ediyoruz
         */
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        /** Bu seeder calistirildiginda once asagidaki tablolari truncate() ediyoruz yani sifirlayip isliyoruz */
        DB::table('permissions')->truncate();
        DB::table('roles')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::table('model_has_permissions')->truncate();

        /** IZINLER baslangic */
        $permissions = [
            // persoal
            [
                'name' => 'personal.profile.view',
                'description' => 'Kullanici bu izne sahipse profilini goruntuleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'personal.profile.edit',
                'description' => 'Kullanici bu izne sahipse profilini duzenleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'personal.password.change',
                'description' => 'Kullanici bu izne sahipse parolasini degistirebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // order
            [
                'name' => 'order.view',
                'description' => 'Kullanici bu izne sahipse siparisleri goruntuleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'order.cancel',
                'description' => 'Kullanici bu izne sahipse siparisleri iptal edebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'order.change-address',
                'description' => 'Kullanici bu izne sahipse siparislerin adresini degistirebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // user
            [
                'name' => 'user.view',
                'description' => 'Kullanici bu izne sahipse kullanicilari goruntuleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user.create',
                'description' => 'Kullanici bu izne sahipse kullanicilari olusturabilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user.edit',
                'description' => 'Kullanici bu izne sahipse kullanicilari duzenleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'user.delete',
                'description' => 'Kullanici bu izne sahipse kullanicilari silebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // category
            [
                'name' => 'category.view',
                'description' => 'Kullanici bu izne sahipse kategori goruntuleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'category.create',
                'description' => 'Kullanici bu izne sahipse kategori olusturabilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'category.edit',
                'description' => 'Kullanici bu izne sahipse kategori duzenleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'category.delete',
                'description' => 'Kullanici bu izne sahipse kategori silebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],

            // product
            [
                'name' => 'product.view',
                'description' => 'Kullanici bu izne sahipse urun goruntuleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'product.create',
                'description' => 'Kullanici bu izne sahipse urun olusturabilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'product.edit',
                'description' => 'Kullanici bu izne sahipse urun duzenleyebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'product.delete',
                'description' => 'Kullanici bu izne sahipse urun silebilir.',
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        // DB::table('permissions')->insert($permissions);
        Permission::insert($permissions);
        /** IZINLER bitis */

        /** ROLLER baslangic */
        $superAdmin = Role::create(['name' => 'super-admin']);
        $member = Role::create(['name' => 'member']);
        $categoryManager = Role::create(['name' => 'category-manager']);
        $orderManager = Role::create(['name' => 'order-manager']);
        $productManager = Role::create(['name' => 'product-manager']);
        $userManager = Role::create(['name' => 'user-manager']);
        /** ROLLER bitis */

        /** ROLLER PERMISSONS baslangic */
        $categoryManagerPermissions = Permission::query()
            ->where('name', 'LIKE', 'category.%')
            ->orWhere('name', 'LIKE', 'personal.%')
            ->get();

        $categoryManager->givePermissionTo($categoryManagerPermissions);
        // kategori yonetimi ve personel yonetimi izinlerini alip category managera atiyoruz.

        $orderManagerPermissions = Permission::query()
            ->where('name', 'LIKE', 'order.%')
            ->orWhere('name', 'LIKE', 'personal.%')
            ->get();

        $orderManager->givePermissionTo($orderManagerPermissions);
        // siparis yonetimi ve personel yonetimi izinlerini alip order managera atiyoruz.

        $userManagerPermissions = Permission::query()
            ->where('name', 'LIKE', 'user.%')
            ->orWhere('name', 'LIKE', 'personal.%')
            ->get();

        $userManager->givePermissionTo($userManagerPermissions);
        // kullanici yonetimi ve personel yonetimi izinlerini alip user managera atiyoruz.

        $productManagerPermissions = Permission::query()
            ->where('name', 'LIKE', 'product.%')
            ->orWhere('name', 'LIKE', 'personal.%')
            ->get();

        $productManager->givePermissionTo($productManagerPermissions);
        // urun yonetimi ve personel yonetimi izinlerini alip product managera atiyoruz.

        $personalManagerPermissions = Permission::query()
            ->orWhere('name', 'LIKE', 'personal.%')
            ->get();

        $member->givePermissionTo($personalManagerPermissions);
        // personel yonetimi izinlerini alip membera atiyoruz.

        $allPermissions = Permission::all();
        $superAdmin->givePermissionTo($allPermissions);
        // tum izinleri alip super admina atiyoruz.

        /** Yukarda truncate icin engelledigimiz iliskileri tekrardan aktif ettik. */
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}