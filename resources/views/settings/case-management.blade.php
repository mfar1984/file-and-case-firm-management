@extends('layouts.app')

@section('breadcrumb')
    Settings > Case Management
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto" x-data="{ 
    showCaseTypeModal: false, 
    showCaseStatusModal: false,
    showEditCaseTypeModal: false,
    showEditCaseStatusModal: false,
    showFilingModal: false,
    caseTypeForm: { code: '', description: '', status: 'active' },
    caseStatusForm: { name: '', description: '', color: 'blue', status: 'active' },
    editCaseTypeForm: { code: '', description: '', status: 'active' },
    editCaseStatusForm: { name: '', description: '', color: 'blue', status: 'active' },
    filingForm: { courtName: '', jurisdiction: '', section: '', caseInitiatorDocument: '', status: 'active' },
    
    openEditCaseTypeModal(code, description, status) {
        this.editCaseTypeForm = { code: code, description: description, status: status };
        this.showEditCaseTypeModal = true;
    },
    
    openEditCaseStatusModal(name, description, color, status) {
        this.editCaseStatusForm = { name: name, description: description, color: color, status: status };
        this.showEditCaseStatusModal = true;
    }
}" x-init="initTagify()">
    <div class="grid grid-cols-1 gap-6">
        <!-- Filing Section -->
        <div>
            <div class="bg-white rounded shadow-md border border-gray-300 mb-6">
                <div class="p-4 md:p-6 border-b border-gray-200">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start">
                        <div class="mb-4 md:mb-0">
                            <div class="flex items-center">
                                <span class="material-icons mr-2 text-purple-600">description</span>
                                <h1 class="text-lg md:text-xl font-bold text-gray-800 text-[14px]">FILING</h1>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 ml-8 text-[11px]">Manage New Filing Case Information</p>
                        </div>
                        
                        <!-- Add Filing Button -->
                        <button @click="showFilingModal = true" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 md:px-3 md:py-1 rounded-md text-sm md:text-xs font-medium flex items-center justify-center md:justify-start w-full md:w-auto">
                            <span class="material-icons text-xs mr-1">add</span>
                            Add Filing
                        </button>
                    </div>
                </div>
                
                <!-- Desktop Table View -->
                <div class="hidden md:block p-6">
                    <div class="overflow-visible border border-gray-200 rounded">
                        <table class="min-w-full border-collapse">
                            <thead>
                                <tr class="bg-primary-light text-white uppercase text-xs">
                                    <th class="py-3 px-4 text-left rounded-tl">Location</th>
                                    <th class="py-3 px-4 text-left">Jurisdiction</th>
                                    <th class="py-3 px-4 text-left">Section</th>
                                    <th class="py-3 px-4 text-left">Case Initiator Document</th>
                                    <th class="py-3 px-4 text-center rounded-tr">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-[11px]">Statement of Claim</td>
                                    <td class="py-3 px-4 text-[11px]">Civil</td>
                                    <td class="py-3 px-4 text-[11px]">Section A</td>
                                    <td class="py-3 px-4 text-[11px]">Writ of Summons</td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center space-x-2 items-center">
                                            <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                                <span class="material-icons text-yellow-700 text-xs">edit</span>
                                            </button>
                                            <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                                <span class="material-icons text-red-600 text-xs">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-[11px]">Defense Statement</td>
                                    <td class="py-3 px-4 text-[11px]">Criminal</td>
                                    <td class="py-3 px-4 text-[11px]">Section B</td>
                                    <td class="py-3 px-4 text-[11px]">Charge Sheet</td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center space-x-2 items-center">
                                            <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                                <span class="material-icons text-yellow-700 text-xs">edit</span>
                                            </button>
                                            <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                                <span class="material-icons text-red-600 text-xs">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-[11px]">Reply to Defense</td>
                                    <td class="py-3 px-4 text-[11px]">Family</td>
                                    <td class="py-3 px-4 text-[11px]">Section C</td>
                                    <td class="py-3 px-4 text-[11px]">Petition</td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center space-x-2 items-center">
                                            <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                                <span class="material-icons text-yellow-700 text-xs">edit</span>
                                            </button>
                                            <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                                <span class="material-icons text-red-600 text-xs">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="py-3 px-4 text-[11px]">Pejabat Tanah</td>
                                    <td class="py-3 px-4 text-[11px]">-</td>
                                    <td class="py-3 px-4 text-[11px]">Conveyancing</td>
                                    <td class="py-3 px-4 text-[11px]">Sale & Purchase Agreement</td>
                                    <td class="py-3 px-4">
                                        <div class="flex justify-center space-x-2 items-center">
                                            <button class="p-1 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100" title="Edit">
                                                <span class="material-icons text-yellow-700 text-xs">edit</span>
                                            </button>
                                            <button class="p-1 bg-red-50 rounded hover:bg-red-100 border border-red-100" title="Delete">
                                                <span class="material-icons text-red-600 text-xs">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View for Filing -->
                <div class="md:hidden p-4 space-y-4">
                    <!-- Filing Card 1 -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-800">Statement of Claim</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                                    <span class="material-icons text-yellow-700 text-sm">edit</span>
                                </button>
                                <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                                    <span class="material-icons text-red-600 text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Jurisdiction:</span>
                                <span class="text-xs text-gray-800">Civil</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Section:</span>
                                <span class="text-xs text-gray-800">Section A</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Document:</span>
                                <span class="text-xs text-gray-800">Writ of Summons</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filing Card 2 -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-800">Defense Statement</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                                    <span class="material-icons text-yellow-700 text-sm">edit</span>
                                </button>
                                <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                                    <span class="material-icons text-red-600 text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Jurisdiction:</span>
                                <span class="text-xs text-gray-800">Criminal</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Section:</span>
                                <span class="text-xs text-gray-800">Section B</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Document:</span>
                                <span class="text-xs text-gray-800">Charge Sheet</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filing Card 3 -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-800">Reply to Defense</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                                    <span class="material-icons text-yellow-700 text-sm">edit</span>
                                </button>
                                <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                                    <span class="material-icons text-red-600 text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Jurisdiction:</span>
                                <span class="text-xs text-gray-800">Family</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Section:</span>
                                <span class="text-xs text-gray-800">Section C</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Document:</span>
                                <span class="text-xs text-gray-800">Petition</span>
                            </div>
                        </div>
                    </div>

                    <!-- Filing Card 4 -->
                    <div class="bg-white border border-gray-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-800">Pejabat Tanah</span>
                            </div>
                            <div class="flex space-x-2">
                                <button class="p-2 bg-yellow-50 rounded hover:bg-yellow-100 border border-yellow-100">
                                    <span class="material-icons text-yellow-700 text-sm">edit</span>
                                </button>
                                <button class="p-2 bg-red-50 rounded hover:bg-red-100 border border-red-100">
                                    <span class="material-icons text-red-600 text-sm">delete</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Jurisdiction:</span>
                                <span class="text-xs text-gray-800">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Section:</span>
                                <span class="text-xs text-gray-800">Conveyancing</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-600">Document:</span>
                                <span class="text-xs text-gray-800">Sale & Purchase Agreement</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Filing Modal -->
        <div x-show="showFilingModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
                
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="material-icons text-purple-600">description</span>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-sm leading-6 font-medium text-gray-900 mb-4">Add Filing</h3>
                                
                                <form class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Location *</label>
                                        <input type="text" name="location" x-model="filingForm.courtName" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                    </div>
                                    
                                    <template x-if="filingForm.courtName !== 'Pejabat Tanah'">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Jurisdiction *</label>
                                            <input type="text" name="jurisdiction" x-model="filingForm.jurisdiction" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                        </div>
                                    </template>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Section *</label>
                                        <select x-model="filingForm.section" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                            <option value="">Select Section</option>
                                            <template x-if="filingForm.courtName !== 'Pejabat Tanah'">
                                                <optgroup label="Court Sections">
                                                    <option value="Criminal">Criminal</option>
                                                    <option value="Sivil">Sivil</option>
                                                    <option value="Probate / Letter of Administration">Probate / Letter of Administration</option>
                                                </optgroup>
                                            </template>
                                            <template x-if="filingForm.courtName === 'Pejabat Tanah'">
                                                <option value="Conveyancing">Conveyancing</option>
                                            </template>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Case Initiator Document *</label>
                                        <input type="text" name="caseInitiatorDocument" x-model="filingForm.caseInitiatorDocument" class="w-full px-3 py-2 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-md text-xs font-medium">
                            Add Filing
                        </button>
                        <button @click="showFilingModal = false" type="button" class="mt-3 bg-white text-gray-700 hover:bg-gray-50 px-3 py-1 rounded-md text-xs font-medium border border-gray-300 sm:mt-0 sm:ml-3">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 

<script>
function initTagify() {
    // Location Tagify
    const locationInput = document.querySelector('input[name="location"]');
    if (locationInput && !locationInput.classList.contains('tagify')) {
        const locationTagify = new Tagify(locationInput, {
            whitelist: [
                'Pejabat Tanah',
                'Dewan Bandaraya Kuala Lumpur',
                'Istana Kehakiman',
                'Kompleks Mahkamah Kangar',
                'Kompleks Mahkamah Alor Setar',
                'Kompleks Mahkamah Sungai Petani',
                'Kompleks Mahkamah Jitra',
                'Kompleks Mahkamah Langkawi',
                'Kompleks Mahkamah Gurun',
                'Kompleks Mahkamah Kulim',
                'Kompleks Mahkamah Baling',
                'Kompleks Mahkamah Yan',
                'Kompleks Mahkamah Kuala Nerang',
                'Kompleks Mahkamah Sik',
                'Kompleks Mahkamah Bandar Baharu',
                'Kompleks Mahkamah Pulau Pinang',
                'Kompleks Mahkamah Butterworth',
                'Kompleks Mahkamah Bukit Mertajam',
                'Kompleks Mahkamah Balik Pulau',
                'Kompleks Mahkamah Jawi',
                'Kompleks Mahkamah Ipoh',
                'Kompleks Mahkamah Taiping',
                'Kompleks Mahkamah Teluk Intan',
                'Kompleks Mahkamah Batu Gajah',
                'Kompleks Mahkamah Sg. Siput',
                'Kompleks Mahkamah Kuala Kangsar',
                'Kompleks Mahkamah Seri Manjung',
                'Kompleks Mahkamah Slim River',
                'Kompleks Mahkamah Parit Buntar',
                'Kompleks Mahkamah Pantai Remis',
                'Kompleks Mahkamah Tanjung Malim',
                'Kompleks Mahkamah Selama',
                'Kompleks Mahkamah Pengkalan Hulu',
                'Kompleks Mahkamah Lenggong',
                'Kompleks Sultan Salahuddin Abdul Aziz Shah',
                'Kompleks Mahkamah Ampang',
                'Kompleks Mahkamah Petaling Jaya',
                'Kompleks Mahkamah Selayang',
                'Kompleks Mahkamah Kuala Kubu Bharu',
                'Kompleks Mahkamah Sungai Besar',
                'Kompleks Mahkamah Gerik',
                'Kompleks Mahkamah Kampar',
                'Kompleks Mahkamah Telok Datok',
                'Kompleks Mahkamah Kajang',
                'Kompleks Mahkamah Bandar Baru Bangi',
                'Kompleks Mahkamah Kuala Selangor',
                'Kompleks Mahkamah Sepang',
                'Kompleks Mahkamah Klang',
                'Kompleks Mahkamah Alor Gajah',
                'Kompleks Mahkamah Seremban',
                'Kompleks Mahkamah Kuala Pilah',
                'Kompleks Mahkamah Tampin',
                'Kompleks Mahkamah Port Dickson',
                'Kompleks Mahkamah Bahau',
                'Kompleks Mahkamah Rembau',
                'Kompleks Mahkamah Gemas',
                'Kompleks Mahkamah Jelebu',
                'Kompleks Mahkamah Melaka',
                'Kompleks Mahkamah Jasin',
                'Kompleks Mahkamah Batu Pahat',
                'Kompleks Mahkamah Muar',
                'Kompleks Mahkamah Segamat',
                'Kompleks Mahkamah Tangkak',
                'Kompleks Mahkamah Kulai',
                'Kompleks Mahkamah Kluang',
                'Kompleks Mahkamah Kota Tinggi',
                'Kompleks Mahkamah Pontian',
                'Kompleks Mahkamah Mersing',
                'Kompleks Mahkamah Yong Peng',
                'Kompleks Mahkamah Kuantan',
                'Kompleks Mahkamah Temerloh',
                'Kompleks Mahkamah Raub',
                'Kompleks Mahkamah Pekan',
                'Kompleks Mahkamah Maran',
                'Kompleks Mahkamah Bentong',
                'Kompleks Mahkamah Cameron Highlands',
                'Kompleks Mahkamah Rompin',
                'Kompleks Mahkamah Kuala Lipis',
                'Kompleks Mahkamah Jerantut',
                'Kompleks Mahkamah Kuala Terengganu',
                'Kompleks Mahkamah Kemaman',
                'Kompleks Mahkamah Dungun',
                'Kompleks Mahkamah Besut',
                'Kompleks Mahkamah Kuala Berang',
                'Kompleks Mahkamah Marang',
                'Kompleks Mahkamah Setiu',
                'Kompleks Mahkamah Kota Bharu',
                'Kompleks Mahkamah Tumpat',
                'Kompleks Mahkamah Tanah Merah',
                'Kompleks Mahkamah Bachok',
                'Kompleks Mahkamah Jeli',
                'Kompleks Mahkamah Pasir Puteh',
                'Kompleks Mahkamah Kuala Krai',
                'Kompleks Mahkamah Machang',
                'Kompleks Mahkamah Gua Musang',
                'Kompleks Mahkamah Pasir Mas',
                'Kompleks Mahkamah Kuala Lumpur',
                'Kompleks Mahkamah Putrajaya',
                'Kompleks Mahkamah Pengerang',
                'Mahkamah Johor Bahru',
                'Mahkamah Majistret (Majlis Bandaraya) Johor Bahru',
                'Mahkamah Sesyen Khas PATI Ajil',
                'Mahkamah Sesyen Khas PATI Lenggeng',
                'Mahkamah Sesyen Khas PATI Machap Umboo',
                'Mahkamah Sesyen Khas PATI Pekan Nanas',
                'Mahkamah Sesyen Khas PATI Semenyih'
            ],
            maxTags: 1,
            enforceWhitelist: false,
            dropdown: {
                maxItems: 20,
                classname: "tags-look",
                enabled: 0,
                closeOnSelect: false
            }
        });

        // Sync Location with Alpine.js
        locationTagify.on('add', function(e) {
            const alpineComponent = document.querySelector('[x-data]').__x.$data;
            alpineComponent.filingForm.courtName = e.detail.data.value;
            if (e.detail.data.value === 'Pejabat Tanah') {
                alpineComponent.filingForm.section = 'Conveyancing';
                alpineComponent.filingForm.jurisdiction = '';
                // Also trigger section change for Case Initiator Document
                if (typeof window.updateCaseInitiatorTagify === 'function') {
                    window.updateCaseInitiatorTagify('Conveyancing');
                }
            }
        });
        locationTagify.on('remove', function(e) {
            const alpineComponent = document.querySelector('[x-data]').__x.$data;
            alpineComponent.filingForm.courtName = '';
            alpineComponent.filingForm.section = '';
            alpineComponent.filingForm.jurisdiction = '';
            if (typeof window.updateCaseInitiatorTagify === 'function') {
                window.updateCaseInitiatorTagify('');
            }
        });

        // Section change watcher for Case Initiator Document Tagify
        window.updateCaseInitiatorTagify = function(section) {
            const caseDocumentInput = document.querySelector('input[name="caseInitiatorDocument"]');
            if (!caseDocumentInput) return;
            if (caseDocumentInput.tagify) {
                caseDocumentInput.tagify.destroy();
            }
            let whitelist = [];
            if (section === 'Conveyancing') {
                whitelist = [
                    'Letter Offer',
                    'Sale & Purchase Agreement',
                    'Loan Agreement/Facility Agreement'
                ];
            } else {
                whitelist = [
                    // Pejabat Tanah Documents
                    'Letter Offer',
                    'Sale & Purchase Agreement',
                    'Loan Agreement/Facility Agreement',
                    // Criminal Documents
                    'Kertas Pertuduhan/Pertuduhan Pilihan',
                    'Permohonan Jenayah/Usul',
                    'Saman dan Kertas Pertuduhan/Pertuduhan Pilihan',
                    // Sivil Documents
                    'Saman Pemula',
                    'Saman Penghutang Penghakiman',
                    'Wirit Pelaksanaan',
                    'Wirit Saman',
                    // Probate Documents
                    'Saman Pemula ( Ex Parte )',
                    'Borang P'
                ];
            }
            const caseDocumentTagify = new Tagify(caseDocumentInput, {
                whitelist: whitelist,
                maxTags: 10,
                enforceWhitelist: false,
                dropdown: {
                    maxItems: 20,
                    classname: "tags-look",
                    enabled: 0,
                    closeOnSelect: false
                }
            });
            // Sync Case Document with Alpine.js
            caseDocumentTagify.on('add', function(e) {
                const alpineComponent = document.querySelector('[x-data]').__x.$data;
                // Store as array of values
                if (!Array.isArray(alpineComponent.filingForm.caseInitiatorDocument)) {
                    alpineComponent.filingForm.caseInitiatorDocument = [];
                }
                alpineComponent.filingForm.caseInitiatorDocument.push(e.detail.data.value);
            });
            caseDocumentTagify.on('remove', function(e) {
                const alpineComponent = document.querySelector('[x-data]').__x.$data;
                if (Array.isArray(alpineComponent.filingForm.caseInitiatorDocument)) {
                    alpineComponent.filingForm.caseInitiatorDocument = alpineComponent.filingForm.caseInitiatorDocument.filter(val => val !== e.detail.data.value);
                }
            });
            caseDocumentInput.tagify = caseDocumentTagify;
        };

        // Section Tagify watcher (if user changes Section manually)
        const sectionSelect = document.querySelector('select[x-model="filingForm.section"]');
        if (sectionSelect) {
            sectionSelect.addEventListener('change', function(e) {
                if (typeof window.updateCaseInitiatorTagify === 'function') {
                    window.updateCaseInitiatorTagify(e.target.value);
                }
            });
        }

        // Initial Tagify for Case Initiator Document (default: all options, multiple tags)
        window.updateCaseInitiatorTagify('');
    }

    // Jurisdiction Tagify
    const jurisdictionInput = document.querySelector('input[name="jurisdiction"]');
    if (jurisdictionInput && !jurisdictionInput.classList.contains('tagify')) {
        const jurisdictionTagify = new Tagify(jurisdictionInput, {
            whitelist: [
                'Mahkamah Persekutuan',
                'Mahkamah Rayuan',
                'Mahkamah Tinggi',
                'Mahkamah Sesyen',
                'Mahkamah Majistret'
            ],
            maxTags: 1,
            enforceWhitelist: false,
            dropdown: {
                maxItems: 20,
                classname: "tags-look",
                enabled: 0,
                closeOnSelect: false
            }
        });

        // Sync Jurisdiction with Alpine.js
        jurisdictionTagify.on('add', function(e) {
            const alpineComponent = document.querySelector('[x-data]').__x.$data;
            alpineComponent.filingForm.jurisdiction = e.detail.data.value;
        });
    }

    // Case Initiator Document Tagify
    const caseDocumentInput = document.querySelector('input[name="caseInitiatorDocument"]');
    if (caseDocumentInput && !caseDocumentInput.classList.contains('tagify')) {
        const caseDocumentTagify = new Tagify(caseDocumentInput, {
            whitelist: [
                // Pejabat Tanah Documents
                'Letter Offer',
                'Sale & Purchase Agreement',
                'Loan Agreement/Facility Agreement',
                // Criminal Documents
                'Kertas Pertuduhan/Pertuduhan Pilihan',
                'Permohonan Jenayah/Usul',
                'Saman dan Kertas Pertuduhan/Pertuduhan Pilihan',
                // Sivil Documents
                'Saman Pemula',
                'Saman Penghutang Penghakiman',
                'Wirit Pelaksanaan',
                'Wirit Saman',
                // Probate Documents
                'Saman Pemula ( Ex Parte )',
                'Borang P'
            ],
            maxTags: 10,
            enforceWhitelist: false,
            dropdown: {
                maxItems: 20,
                classname: "tags-look",
                enabled: 0,
                closeOnSelect: false
            }
        });

        // Sync Case Document with Alpine.js
        caseDocumentTagify.on('add', function(e) {
            const alpineComponent = document.querySelector('[x-data]').__x.$data;
            // Store as array of values
            if (!Array.isArray(alpineComponent.filingForm.caseInitiatorDocument)) {
                alpineComponent.filingForm.caseInitiatorDocument = [];
            }
            alpineComponent.filingForm.caseInitiatorDocument.push(e.detail.data.value);
        });
        caseDocumentTagify.on('remove', function(e) {
            const alpineComponent = document.querySelector('[x-data]').__x.$data;
            if (Array.isArray(alpineComponent.filingForm.caseInitiatorDocument)) {
                alpineComponent.filingForm.caseInitiatorDocument = alpineComponent.filingForm.caseInitiatorDocument.filter(val => val !== e.detail.data.value);
            }
        });
        caseDocumentInput.tagify = caseDocumentTagify;
    }
}

// Initialize Tagify when page loads
document.addEventListener('DOMContentLoaded', function() {
    initTagify();
});
</script> 