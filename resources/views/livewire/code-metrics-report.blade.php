<div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Code Metrics Report</h1>
            <p class="text-lg text-gray-600 dark:text-gray-400">Analyze your codebase metrics and coverage</p>
        </div>

        <!-- Error Alert -->
        @if ($error)
            <div class="mb-6 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-400 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-300">Error Loading Report</h3>
                        <p class="mt-2 text-sm text-red-700 dark:text-red-400">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Available -->
        @if ($report)
            <!-- View Tabs -->
            <div class="mb-6 flex gap-2">
                <button wire:click="changeView('overview')"
                    class="px-4 py-2 rounded-lg font-medium transition {{ $selectedView === 'overview' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Overview
                </button>
                <button wire:click="changeView('details')"
                    class="px-4 py-2 rounded-lg font-medium transition {{ $selectedView === 'details' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Details
                </button>
                <button wire:click="changeView('raw')"
                    class="px-4 py-2 rounded-lg font-medium transition {{ $selectedView === 'raw' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                    <svg class="inline-block w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 20l4-16m4 4l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                    Raw
                </button>
            </div>

            <!-- Overview Tab -->
            @if ($selectedView === 'overview')
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    @php
                        $metrics = [
                            ['label' => 'Total Lines', 'value' => $report['summary']['total_lines'] ?? 0, 'icon' => '📊'],
                            ['label' => 'Code Lines', 'value' => $report['summary']['code_lines'] ?? 0, 'icon' => '💻'],
                            ['label' => 'Comment Lines', 'value' => $report['summary']['comment_lines'] ?? 0, 'icon' => '💬'],
                            ['label' => 'Blank Lines', 'value' => $report['summary']['blank_lines'] ?? 0, 'icon' => '⬜'],
                        ];
                    @endphp

                    @foreach ($metrics as $metric)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm font-medium">{{ $metric['label'] }}</p>
                                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($metric['value']) }}</p>
                                </div>
                                <div class="text-4xl">{{ $metric['icon'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Info -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Report Summary</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Report Generated</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $report['generated_at'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Files Analyzed</span>
                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ count($report['files'] ?? []) }}</span>
                        </div>
                        @if (isset($report['summary']['code_coverage']))
                            <div class="flex justify-between py-2">
                                <span class="text-gray-600 dark:text-gray-400">Code Coverage</span>
                                <span class="font-semibold text-green-600 dark:text-green-400">{{ $report['summary']['code_coverage'] }}%</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Details Tab -->
            @if ($selectedView === 'details')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">File Metrics</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <tr>
                                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">File</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">Lines</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">Code</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">Comments</th>
                                    <th class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">Blank</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($report['files'] ?? [] as $file)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="px-6 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $file['name'] ?? 'N/A' }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">{{ $file['total_lines'] ?? 0 }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">{{ $file['code_lines'] ?? 0 }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">{{ $file['comment_lines'] ?? 0 }}</td>
                                        <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">{{ $file['blank_lines'] ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                            No file details available
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Raw Tab -->
            @if ($selectedView === 'raw')
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Raw JSON Data</h2>
                    </div>
                    <pre class="p-6 bg-gray-900 text-gray-100 text-sm overflow-auto max-h-96"><code>{{ json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</code></pre>
                </div>
            @endif
        @else
            <!-- No Report Available -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No Report Available</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Code metrics report has not been generated yet. Run your build pipeline to generate one.</p>
            </div>
        @endif

        <!-- Refresh Button -->
        <div class="mt-8 text-center">
            <button wire:click="loadReport"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Refresh Report
            </button>
        </div>
    </div>
</div>
