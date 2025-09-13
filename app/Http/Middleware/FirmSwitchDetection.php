<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FirmSwitchDetection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only apply to authenticated users
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        
        // Only apply to Super Administrators who can switch firms
        if (!$user->hasRole('Super Administrator')) {
            return $next($request);
        }

        // Check if this is a firm switching request
        if ($request->has('switch_firm')) {
            // Store the previous firm for potential redirect
            session(['previous_firm_id' => session('current_firm_id')]);
            return $next($request);
        }

        // Check if we're accessing a specific resource (edit/show pages)
        $routeName = $request->route()->getName();
        $resourceRoutes = [
            'case.edit', 'case.show',
            'client.edit', 'client.show', 
            'quotation.edit', 'quotation.show',
            'pre-quotation.edit', 'pre-quotation.show',
            'tax-invoice.edit', 'tax-invoice.show',
            'receipt.edit', 'receipt.show',
            'voucher.edit', 'voucher.show',
            'bill.edit', 'bill.show'
        ];

        if (in_array($routeName, $resourceRoutes)) {
            // Get the resource ID from route parameters
            $resourceId = $request->route('id') ?? 
                         $request->route('case') ?? 
                         $request->route('client') ?? 
                         $request->route('quotation') ?? 
                         $request->route('pre_quotation') ?? 
                         $request->route('tax_invoice') ?? 
                         $request->route('receipt') ?? 
                         $request->route('voucher') ?? 
                         $request->route('bill');

            if ($resourceId) {
                // Check if user just switched firms and is trying to access a resource
                $currentFirmId = session('current_firm_id');
                $previousFirmId = session('previous_firm_id');
                
                if ($previousFirmId && $currentFirmId !== $previousFirmId) {
                    // User switched firms, check if resource belongs to new firm
                    $resourceBelongsToNewFirm = $this->checkResourceFirmOwnership($routeName, $resourceId, $currentFirmId);
                    
                    if (!$resourceBelongsToNewFirm) {
                        // Resource doesn't belong to new firm, redirect to index with warning
                        $resourceType = $this->getResourceTypeFromRoute($routeName);
                        
                        // Clear the previous firm session
                        session()->forget('previous_firm_id');
                        
                        return redirect()->route($resourceType . '.index')
                            ->with('warning', 'You have switched firms. The previous resource is not accessible in the current firm context.');
                    }
                    
                    // Clear the previous firm session if resource is accessible
                    session()->forget('previous_firm_id');
                }
            }
        }

        return $next($request);
    }

    /**
     * Check if a resource belongs to the specified firm
     */
    private function checkResourceFirmOwnership($routeName, $resourceId, $firmId): bool
    {
        try {
            switch (true) {
                case str_contains($routeName, 'case'):
                    return \App\Models\CourtCase::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'client'):
                    return \App\Models\Client::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'quotation'):
                    return \App\Models\Quotation::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'pre-quotation'):
                    return \App\Models\PreQuotation::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'tax-invoice'):
                    return \App\Models\TaxInvoice::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'receipt'):
                    return \App\Models\Receipt::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'voucher'):
                    return \App\Models\Voucher::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                case str_contains($routeName, 'bill'):
                    return \App\Models\Bill::withoutFirmScope()
                        ->where('id', $resourceId)
                        ->where('firm_id', $firmId)
                        ->exists();
                        
                default:
                    return false;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get resource type from route name for redirect
     */
    private function getResourceTypeFromRoute($routeName): string
    {
        if (str_contains($routeName, 'case')) return 'case';
        if (str_contains($routeName, 'client')) return 'client';
        if (str_contains($routeName, 'quotation')) return 'quotation';
        if (str_contains($routeName, 'pre-quotation')) return 'pre-quotation';
        if (str_contains($routeName, 'tax-invoice')) return 'tax-invoice';
        if (str_contains($routeName, 'receipt')) return 'receipt';
        if (str_contains($routeName, 'voucher')) return 'voucher';
        if (str_contains($routeName, 'bill')) return 'bill';
        
        return 'dashboard';
    }
}
