{{-- 
    Centralized Pagination Component
    Usage: @include('components.pagination', ['prefix' => 'Types'])
    
    Required JavaScript variables per section:
    - currentPage{Prefix}
    - perPage{Prefix}
    - filtered{Prefix} (array)
    
    Required JavaScript functions per section:
    - goToPage{Prefix}(page)
    - firstPage{Prefix}()
    - lastPage{Prefix}()
    - previousPage{Prefix}()
    - nextPage{Prefix}()
--}}

@props(['prefix' => ''])

<style>
/* Force circular buttons for pagination */
.pagination-btn-circle {
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
}
</style>

<!-- Pagination Section -->
<div class="p-6">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <!-- Left: Page Info -->
        <div class="text-xs text-gray-600">
            <span id="pageInfo{{ $prefix }}">Showing 0 to 0 of 0 records</span>
        </div>

        <!-- Right on Desktop, Center on Mobile: Pagination -->
        <div class="flex items-center justify-center md:justify-end gap-1">
            <button id="prevBtn{{ $prefix }}" onclick="firstPage{{ $prefix }}()"
                    class="pagination-btn-circle text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                &laquo;
            </button>

            <button id="prevSingleBtn{{ $prefix }}" onclick="previousPage{{ $prefix }}()"
                    class="pagination-btn-circle text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                &lsaquo;
            </button>

            <div id="pageNumbers{{ $prefix }}" class="flex items-center gap-1 mx-2">
                <!-- Page numbers will be populated by JavaScript -->
            </div>

            <button id="nextSingleBtn{{ $prefix }}" onclick="nextPage{{ $prefix }}()"
                    class="pagination-btn-circle text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                &rsaquo;
            </button>

            <button id="nextBtn{{ $prefix }}" onclick="lastPage{{ $prefix }}()"
                    class="pagination-btn-circle text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                &raquo;
            </button>
        </div>
    </div>
</div>
