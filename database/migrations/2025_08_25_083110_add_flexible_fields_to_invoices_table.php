<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Product and company information
            $table->string('product_image')->nullable()->after('notes');
            $table->string('company_logo')->nullable()->after('product_image');
            $table->string('company_name')->default('Idefu Furniture')->after('company_logo');
            $table->text('company_address')->nullable()->after('company_name');
            $table->string('company_phone')->nullable()->after('company_address');
            $table->string('company_email')->nullable()->after('company_phone');
            $table->string('company_website')->nullable()->after('company_email');
            
            // Shipping information
            $table->text('shipping_address')->nullable()->after('company_website');
            $table->decimal('shipping_cost', 15, 2)->default(0)->after('shipping_address');
            $table->string('shipping_method')->nullable()->after('shipping_cost');
            
            // Payment information
            $table->string('payment_method')->nullable()->after('shipping_method');
            $table->string('bank_name')->nullable()->after('payment_method');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_holder')->nullable()->after('account_number');
            
            // Invoice customization
            $table->string('po_number')->nullable()->after('account_holder');
            $table->string('seller_name')->nullable()->after('po_number');
            $table->text('terms_conditions')->nullable()->after('seller_name');
            $table->text('notes_customer')->nullable()->after('terms_conditions');
            
            // Payment tracking
            $table->decimal('paid_amount', 15, 2)->default(0)->after('notes_customer');
            $table->decimal('remaining_amount', 15, 2)->default(0)->after('paid_amount');
            $table->date('payment_date')->nullable()->after('remaining_amount');
            
            // Invoice status tracking
            $table->enum('payment_status', ['Unpaid', 'Partial', 'Paid'])->default('Unpaid')->after('payment_date');
            $table->boolean('is_revised')->default(false)->after('payment_status');
            $table->string('revision_reason')->nullable()->after('is_revised');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'product_image',
                'company_logo',
                'company_name',
                'company_address',
                'company_phone',
                'company_email',
                'company_website',
                'shipping_address',
                'shipping_cost',
                'shipping_method',
                'payment_method',
                'bank_name',
                'account_number',
                'account_holder',
                'po_number',
                'seller_name',
                'terms_conditions',
                'notes_customer',
                'paid_amount',
                'remaining_amount',
                'payment_date',
                'payment_status',
                'is_revised',
                'revision_reason'
            ]);
        });
    }
};
