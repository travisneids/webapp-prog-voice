<x-app-layout>
    <x-slot name="header">
        Podium Voice Battle Station
            <button type="button" id="initiateButton" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Go Online
            </button>
    </x-slot>

    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-2">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <label for="callStatusInput" class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <input id="callStatusInput" name="callStatusInput" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full h-full sm:text-sm border-gray-300 rounded-md" placeholder="Click the Initiate Device button to begin..." readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <label for="phoneNumberInput" class="block text-sm font-medium text-gray-700">Enter Phone Number</label>
                        <div class="mt-1">
                            <input type="text" name="phoneNumberInput" id="phoneNumberInput" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block sm:text-sm border-gray-300 rounded-md" placeholder="+16126551234">
                        </div>
                    </div>
                    <button type="button" id="dialButton" class="dialButton inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-lime-600 hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500" disabled>
                        Dial
                    </button>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6 bg-white border-b border-gray-200">
                    <fieldset>
                        <legend class="text-base font-medium text-gray-900">Features</legend>
                        <div class="mt-4 space-y-4">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="webapp" name="webapp" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="webapp" class="font-medium text-gray-700">Webapp</label>
                                    <p class="text-gray-500">Enable the web app to make & receive calls.</p>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="hardphone" name="hardphone" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="hardphone" class="font-medium text-gray-700">Hardphone</label>
                                    <p class="text-gray-500">Route calls to your hardphone (SIP domain).</p>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <h2>Call Controls</h2>
                    </div>
                    <div id="callControlsContainer">
                        <button type="button" id="answerButton" class="answerButton inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-lime-600 hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 disabled:opacity-25 disabled:bg-gray-500 disabled:cursor-not-allowed" disabled>
                            Answer Call
                        </button>
                        <button type="button" id="hangUpButton" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-rose-600 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 disabled:opacity-25 disabled:bg-gray-500 disabled:cursor-not-allowed" disabled onclick="hangUp()">
                            End Call
                        </button>
                        <button type="button" id="holdButton" class="inline-flex items-center px-4 py-2 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 disabled:bg-gray-500 disabled:cursor-not-allowed" disabled onclick="putConferenceOnHold()">
                            Hold
                        </button>
                    </div>

                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="hide" id="log"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
