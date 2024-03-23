@if ($paginator->hasPages())
<nav class="flex flex-col md:flex-row justify-between items-start md:items-center space-y-3 md:space-y-0 p-4"
    aria-label="Table navigation">
    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
        Showing
        <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
        to
        <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
        of
        <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
    </span>
    <ul class="inline-flex items-stretch -space-x-px">
        <li>
            @if ($paginator->onFirstPage())
            <span
                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 dark:border-gray-700">
                <span class="sr-only">Previous</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            @else
            <a href="{{ $paginator->previousPageUrl() }}"
                class="flex items-center justify-center h-full py-1.5 px-3 ml-0 text-gray-500 bg-white rounded-l-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only">Previous</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            @endif
        </li>
        @if ($paginator->lastPage() > 1)
        <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-between">
            <ul class="pagination">
                {{-- First Page Link --}}
                <li>
                    <a href="{{ $paginator->url(1) }}"
                        class="pagination-item active flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white  {{ $paginator->currentPage() == 1 ? 'pagination-current active flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-100 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : '' }}"
                        aria-label="First Page">1</a>
                </li>

                {{-- Separator after First Page --}}
                @if ($paginator->currentPage() > 3)
                <li>
                    <span
                        class="pagination-ellipsis flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border-y border-r border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</span>
                </li>
                @endif

                {{-- Pages --}}
                @for ($i = max(2, $paginator->currentPage() - 1); $i <= min($paginator->lastPage() - 1,
                    $paginator->currentPage() + 1); $i++)
                    <li>
                        <a href="{{ $paginator->url($i) }}"
                            class="pagination-item active flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border-y border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white  {{ $paginator->currentPage() == $i ? 'pagination-current active flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-100 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : '' }}"
                            aria-label="Page {{ $i }}">{{ $i }}</a>
                    </li>

                    @endfor

                    {{-- Separator before Last Page --}}
                    @if ($paginator->currentPage() < $paginator->lastPage() - 2)
                        <li>
                            <span
                                class="pagination-ellipsis flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border-y border-l border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">...</span>
                        </li>
                        @endif

                        {{-- Last Page Link --}}
                        <li>
                            <a href="{{ $paginator->url($paginator->lastPage()) }}"
                                class="pagination-item active flex items-center justify-center text-sm py-2 px-3 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white  {{ $paginator->currentPage() == $paginator->lastPage() ? 'pagination-current active flex items-center justify-center text-sm z-10 py-2 px-3 leading-tight text-primary-600 bg-primary-100 border border-primary-300 hover:bg-primary-100 hover:text-primary-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white' : '' }}"
                                aria-label="Last Page">
                                {{ $paginator->lastPage()}}</a>
                        </li>

            </ul>
        </nav>
        @endif
        <li>
            @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                <span class="sr-only">Next</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </a>
            @else
            <span
                class="flex items-center justify-center h-full py-1.5 px-3 leading-tight text-gray-500 bg-white rounded-r-lg border border-gray-300 dark:border-gray-700">
                <span class="sr-only">Next</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                        clip-rule="evenodd" />
                </svg>
            </span>
            @endif
        </li>
    </ul>
</nav>
@endif