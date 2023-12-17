<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
class ShieldSeeder extends Seeder
{
    /**
    * Run the database seeds.
    *
    * @return void
    */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"Admin","guard_name":"web","permissions":[]},{"name":"panel_user","guard_name":"web","permissions":[]},{"name":"Accountant","guard_name":"web","permissions":[]},{"name":"Loan Officer","guard_name":"web","permissions":[]},{"name":"Director(Finance)","guard_name":"web","permissions":[]},{"name":"Director(Legal)","guard_name":"web","permissions":[]},{"name":"Operations manager","guard_name":"web","permissions":[]},{"name":"super_admin","guard_name":"web","permissions":["view_approval::flow","view_any_approval::flow","create_approval::flow","update_approval::flow","restore_approval::flow","restore_any_approval::flow","replicate_approval::flow","reorder_approval::flow","delete_approval::flow","delete_any_approval::flow","force_delete_approval::flow","force_delete_any_approval::flow","view_borrower","view_any_borrower","create_borrower","update_borrower","restore_borrower","restore_any_borrower","replicate_borrower","reorder_borrower","delete_borrower","delete_any_borrower","force_delete_borrower","force_delete_any_borrower","view_branch","view_any_branch","create_branch","update_branch","restore_branch","restore_any_branch","replicate_branch","reorder_branch","delete_branch","delete_any_branch","force_delete_branch","force_delete_any_branch","view_charge","view_any_charge","create_charge","update_charge","restore_charge","restore_any_charge","replicate_charge","reorder_charge","delete_charge","delete_any_charge","force_delete_charge","force_delete_any_charge","view_loan","view_any_loan","create_loan","update_loan","restore_loan","restore_any_loan","replicate_loan","reorder_loan","delete_loan","delete_any_loan","force_delete_loan","force_delete_any_loan","repay_loan","view_loan::application","view_any_loan::application","create_loan::application","update_loan::application","restore_loan::application","restore_any_loan::application","replicate_loan::application","reorder_loan::application","delete_loan::application","delete_any_loan::application","force_delete_loan::application","force_delete_any_loan::application","disburse_loan::application","repay_loan::application","recalculate_loan::application","cancel_loan::application","view_loan::disbursement","view_any_loan::disbursement","create_loan::disbursement","update_loan::disbursement","restore_loan::disbursement","restore_any_loan::disbursement","replicate_loan::disbursement","reorder_loan::disbursement","delete_loan::disbursement","delete_any_loan::disbursement","force_delete_loan::disbursement","force_delete_any_loan::disbursement","disburse_loan::disbursement","repay_loan::disbursement","recalculate_loan::disbursement","cancel_loan::disbursement","view_loan::product","view_any_loan::product","create_loan::product","update_loan::product","restore_loan::product","restore_any_loan::product","replicate_loan::product","reorder_loan::product","delete_loan::product","delete_any_loan::product","force_delete_loan::product","force_delete_any_loan::product","view_loan::repayment","view_any_loan::repayment","create_loan::repayment","update_loan::repayment","restore_loan::repayment","restore_any_loan::repayment","replicate_loan::repayment","reorder_loan::repayment","delete_loan::repayment","delete_any_loan::repayment","force_delete_loan::repayment","force_delete_any_loan::repayment","view_organisation","view_any_organisation","create_organisation","update_organisation","restore_organisation","restore_any_organisation","replicate_organisation","reorder_organisation","delete_organisation","delete_any_organisation","force_delete_organisation","force_delete_any_organisation","view_penalty","view_any_penalty","create_penalty","update_penalty","restore_penalty","restore_any_penalty","replicate_penalty","reorder_penalty","delete_penalty","delete_any_penalty","force_delete_penalty","force_delete_any_penalty","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_LoanCalculator","page_MyProfilePage"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions,true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (! blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web'
                            ]));
                        });
                    $role->syncPermissions($permissionModels);

                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions,true))) {

            foreach($permissions as $permission) {

                if (Utils::getPermissionModel()::whereName($permission)->doesntExist()) {
                    Utils::getPermissionModel()::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
