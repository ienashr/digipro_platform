<?php

namespace App\Actions\Utility\Dashboard;

class GetSidebarMenuAction
{
    public function handle()
    {
        return [
            [
                'text' => 'Dashboard',
                'url'  => route('dashboard.index'),
                'icon' => 'VDashboard',
                'can'  => 'view_general_dashboard'
            ],
            [
                'text' => 'Category',
                'url'  => route('category.index'),
                'icon' => 'VTag',
                'can'  => 'view_category'
            ],
            [
                'text' => 'Product',
                'url'  => route('product.index'),
                'icon' => 'VProduct',
                'can'  => 'view_product'
            ],
            [
                'text' => 'Customer',
                'url'  => route('customer.index'),
                'icon' => 'VBook',
                'can'  => 'view_customer'
            ],

            [
                'text' => 'Order',
                'url'  => route('order.index'),
                'icon' => 'VTransaction',
                'can'  => 'view_order'
            ],
         
            [
                'text' => 'Settings',
                'icon' => 'VSetting',
                'group' => true,
                'can'  => ['view_settings_role_management', 'view_settings_user_management'],
                'submenu' => [
                    [
                        'text' => 'Role Management',
                        'url'  => route('settings.role.index'),
                        'can'  => 'view_settings_role_management',
                    ],
                    [
                        'text' => 'User Management',
                        'url'  => route('settings.user.index'),
                        'can'  => 'view_settings_user_management',
                    ],
                ],
            ],
        ];
    }
}