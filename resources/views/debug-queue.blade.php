<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Debugger</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Queue Debugger</h1>
            <div class="flex gap-4">
                <a href="/debug-queue" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Refresh
                </a>
                <form action="/process-queue" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Process Queue Now
                    </button>
                </form>
            </div>
        </div>

        @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('status') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Mail Configuration -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-blue-600">Mail Configuration</h2>
                @if($mailConfig)
                    <div class="text-sm">
                        <p><span class="font-bold">Source:</span> Database (MailConfiguration table)</p>
                        <p><span class="font-bold">Host:</span> {{ $mailConfig->mail_host }}</p>
                        <p><span class="font-bold">Port:</span> {{ $mailConfig->mail_port }}</p>
                        <p><span class="font-bold">Username:</span> {{ $mailConfig->mail_username }}</p>
                        <p><span class="font-bold">Encryption:</span> {{ $mailConfig->mail_encryption }}</p>
                        <p class="mt-2 text-yellow-600 bg-yellow-50 p-2 rounded">Note: Database settings override .env settings.</p>
                    </div>
                @else
                    <div class="text-sm">
                        <p><span class="font-bold">Source:</span> .env File</p>
                        <p class="text-gray-500 italic">No active MailConfiguration found in database.</p>
                    </div>
                @endif
            </div>

            <!-- Connection Status -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-purple-600">SMTP Connection Test</h2>
                <div class="text-sm space-y-3">
                    <div>
                        <p class="font-bold">Port 587 (TLS):</p>
                        <p class="{{ str_contains($connectionStatus587, 'Failed') ? 'text-red-600' : 'text-green-600' }}">
                            {{ $connectionStatus587 }}
                        </p>
                    </div>
                    <div>
                        <p class="font-bold">Port 465 (SSL):</p>
                        <p class="{{ str_contains($connectionStatus465, 'Failed') ? 'text-red-600' : 'text-green-600' }}">
                            {{ $connectionStatus465 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Pending Jobs -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-yellow-600 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Pending Jobs ({{ $pendingJobs->count() }})
                </h2>
                @if($pendingJobs->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-left">ID</th>
                                    <th class="px-4 py-2 text-left">Queue</th>
                                    <th class="px-4 py-2 text-left">Attempts</th>
                                    <th class="px-4 py-2 text-left">Available At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($pendingJobs as $job)
                                <tr>
                                    <td class="px-4 py-2 font-mono">{{ $job->id }}</td>
                                    <td class="px-4 py-2">{{ $job->queue }}</td>
                                    <td class="px-4 py-2">{{ $job->attempts }}</td>
                                    <td class="px-4 py-2">{{ date('Y-m-d H:i:s', $job->available_at) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 italic">No pending jobs.</p>
                @endif
            </div>

            <!-- Failed Jobs -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-red-600 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Failed Jobs ({{ $failedJobs->count() }})
                </h2>
                @if($failedJobs->count() > 0)
                    <div class="space-y-4">
                        @foreach($failedJobs as $job)
                        <div class="border border-red-100 bg-red-50 rounded p-3">
                            <div class="flex justify-between text-xs text-red-800 mb-1">
                                <span class="font-bold">ID: {{ $job->id }}</span>
                                <span>{{ $job->failed_at }}</span>
                            </div>
                            <div class="text-xs text-red-600 font-mono break-all h-20 overflow-y-auto bg-white p-2 rounded border border-red-100">
                                {{ $job->exception }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic">No failed jobs.</p>
                @endif
            </div>
        </div>
        
        <div class="mt-8 text-center text-gray-500 text-sm">
            <p>If pending jobs are stuck, click "Process Queue Now" to force execution.</p>
        </div>
    </div>
</body>
</html>