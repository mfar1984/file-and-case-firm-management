@extends('layouts.app')

@section('breadcrumb')
    {{ isset($case) ? 'Case > Edit Case' : 'Case > Add New Case' }}
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">{{ isset($case) ? 'edit' : 'add_circle' }}</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">{{ isset($case) ? 'Edit Case' : 'Add New Case' }}</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">{{ isset($case) ? 'Update the case details.' : 'Create a new court case with all necessary details.' }}</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form method="POST" action="{{ isset($case) ? route('case.update', $case->id) : route('case.store') }}" enctype="multipart/form-data" class="space-y-0" x-data="{
                plaintiffs: @js(isset($case) ? $case->parties->where('party_type','plaintiff')->map(function($p){
                    return [
                        'name' => $p->name,
                        'ic' => $p->ic_passport,
                        'phone' => $p->phone,
                        'email' => $p->email,
                        'gender' => $p->gender,
                        'nationality' => $p->nationality,
                        'client_id' => null,
                    ];
                })->values() : []),
                defendants: @js(isset($case) ? $case->parties->where('party_type','defendant')->map(function($p){
                    return [
                        'name' => $p->name,
                        'ic' => $p->ic_passport,
                        'phone' => $p->phone,
                        'email' => $p->email,
                        'gender' => $p->gender,
                        'nationality' => $p->nationality,
                        'client_id' => null,
                    ];
                })->values() : []),
                partners: @js(isset($case) ? $case->partners->map(function($cp){
                    return [
                        'partner_id' => $cp->partner_id,
                        'name' => optional($cp->partner)->incharge_name,
                        'firm_name' => optional($cp->partner)->firm_name,
                        'email' => optional($cp->partner)->incharge_email,
                        'contact' => optional($cp->partner)->incharge_contact,
                        'specialization' => optional($cp->partner)->specialization,
                        'role' => $cp->role,
                    ];
                })->values() : []),
                documents: @js(isset($case) ? $case->files->map(function($f){
                    return [
                        'existing' => true,
                        'id' => $f->id,
                        'file_name' => $f->file_name,
                        'file_size' => $f->file_size,
                        'type' => $f->category_id,
                        'filed_by' => $f->taken_by,
                        'filing_date' => optional($f->created_at)->format('Y-m-d'),
                        'status' => $f->status,
                    ];
                })->values() : []),
                totalClaim: @js(isset($case) ? (float)($case->claim_amount ?? 0) : 0),
                showPlaintiffDropdown: false,
                showDefendantDropdown: false,
                showPartnerDropdown: false,
                selectedPlaintiff: '',
                selectedDefendant: '',
                selectedPartner: '',
                clientList: [
                    @foreach($clients as $client)
                    { 
                        id: {{ $client->id }}, 
                        name: '{{ $client->name }}', 
                        ic: '{{ $client->ic_passport ?? '' }}', 
                        phone: '{{ $client->phone ?? '' }}', 
                        email: '{{ $client->email ?? '' }}', 
                        party_type: '{{ $client->party_type ?? '' }}',
                        gender: '{{ $client->gender ?? '' }}',
                        nationality: '{{ $client->nationality ?? '' }}'
                    },
                    @endforeach
                ],
                partnerList: [
                    @foreach($partners as $partner)
                    { id: {{ $partner->id }}, name: '{{ $partner->incharge_name }}', firm_name: '{{ $partner->firm_name }}', email: '{{ $partner->incharge_email }}', contact: '{{ $partner->incharge_contact }}', specialization: '{{ $partner->specialization }}' },
                    @endforeach
                ],
                
                // Computed properties for filtered client lists
                get applicantList() {
                    return this.clientList.filter(client => client.party_type === 'applicant');
                },
                get respondentList() {
                    return this.clientList.filter(client => client.party_type === 'respondent');
                },
                
                // IC lookup function
                findClientByIC(ic) {
                    return this.clientList.find(client => client.ic === ic);
                },
                
                // Handle IC input change
                handleICChange(ic, type, index) {
                    const client = this.findClientByIC(ic);
                    
                    if (client) {
                        if (type === 'plaintiff') {
                            this.plaintiffs[index] = {
                                name: client.name,
                                ic: client.ic,
                                phone: client.phone,
                                email: client.email,
                                gender: client.gender,
                                nationality: client.nationality,
                                client_id: client.id
                            };
                        } else if (type === 'defendant') {
                            this.defendants[index] = {
                                name: client.name,
                                ic: client.ic,
                                phone: client.phone,
                                email: client.email,
                                gender: client.gender,
                                nationality: client.nationality,
                                client_id: client.id
                            };
                        }
                    }
                },
                
                // File upload handling
                handleFileUpload(event, index) {
                    const file = event.target.files[0];
                    if (file) {
                        this.documents[index].file = file;
                        this.documents[index].file_name = file.name;
                        this.documents[index].file_size = this.formatFileSize(file.size);
                    }
                },
                
                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },
                
                addPlaintiff() {
                    this.plaintiffs.push({ 
                        name: '', 
                        ic: '', 
                        phone: '', 
                        email: '', 
                        gender: '', 
                        nationality: '', 
                        client_id: null 
                    });
                },
                removePlaintiff(index) {
                    this.plaintiffs.splice(index, 1);
                },
                addDefendant() {
                    this.defendants.push({ 
                        name: '', 
                        ic: '', 
                        phone: '', 
                        email: '', 
                        gender: '', 
                        nationality: '', 
                        client_id: null 
                    });
                },
                removeDefendant(index) {
                    this.defendants.splice(index, 1);
                },
                addPartner() {
                    this.partners.push({ partner_id: '', role: '' });
                },
                removePartner(index) {
                    this.partners.splice(index, 1);
                },
                addDocument() {
                    this.documents.push({
                        existing: false,
                        id: null,
                        file: null,
                        file_name: '',
                        file_size: '',
                        type: '',
                        filed_by: '',
                        filing_date: '',
                        status: 'pending'
                    });
                },
                removeDocument(index) {
                    this.documents.splice(index, 1);
                },
                addSelectedPlaintiff() {
                    if (this.selectedPlaintiff) {
                        const client = this.clientList.find(c => c.id == this.selectedPlaintiff);
                        if (client) {
                            this.plaintiffs.push({ 
                                name: client.name, 
                                ic: client.ic, 
                                phone: client.phone,
                                email: client.email,
                                gender: client.gender,
                                nationality: client.nationality,
                                client_id: client.id
                            });
                        }
                        this.selectedPlaintiff = '';
                        this.showPlaintiffDropdown = false;
                    }
                },
                addSelectedDefendant() {
                    if (this.selectedDefendant) {
                        const client = this.clientList.find(c => c.id == this.selectedDefendant);
                        if (client) {
                            this.defendants.push({ 
                                name: client.name, 
                                ic: client.ic, 
                                phone: client.phone,
                                email: client.email,
                                gender: client.gender,
                                nationality: client.nationality,
                                client_id: client.id
                            });
                        }
                        this.selectedDefendant = '';
                        this.showDefendantDropdown = false;
                    }
                },
                addSelectedPartner() {
                    if (this.selectedPartner) {
                        const partner = this.partnerList.find(p => p.id == this.selectedPartner);
                        if (partner) {
                            this.partners.push({ partner_id: partner.id, name: partner.name, firm_name: partner.firm_name, email: partner.email, contact: partner.contact, specialization: partner.specialization, role: '' });
                        }
                        this.selectedPartner = '';
                        this.showPartnerDropdown = false;
                    }
                },
                handleClientSelection(ic, type, index) {
                    const client = this.findClientByIC(ic);
                    if (client) {
                        if (type === 'plaintiff') {
                            this.plaintiffs[index] = {
                                name: client.name,
                                ic: client.ic,
                                phone: client.phone,
                                email: client.email,
                                gender: client.gender,
                                nationality: client.nationality,
                                client_id: client.id
                            };
                        } else if (type === 'defendant') {
                            this.defendants[index] = {
                                name: client.name,
                                ic: client.ic,
                                phone: client.phone,
                                email: client.email,
                                gender: client.gender,
                                nationality: client.nationality,
                                client_id: client.id
                            };
                        }
                    }
                }
            }" @submit.prevent="window.submitCaseForm($el)">
                @csrf
                @if(isset($case))
                    @method('PUT')
                @endif

                <!-- Case Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-blue-600 text-base mr-2">gavel</span>
                        Case Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the basic case details and court information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Reference *</label>
                            <input type="text" name="case_ref" value="{{ old('case_ref', $case->case_number ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 2025-001" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Person In Charge *</label>
                            <input type="text" name="person_in_charge" value="{{ old('person_in_charge', $case->person_in_charge ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Ahmad bin Ali" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Court Reference</label>
                            <input type="text" name="court_ref" value="{{ old('court_ref', $case->court_ref ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., CR-2025-001">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Type *</label>
                            <select name="case_type_id" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select case type...</option>
                                @foreach($caseTypes as $caseType)
                                    <option value="{{ $caseType->id }}" {{ old('case_type_id', $case->case_type_id ?? null) == $caseType->id ? 'selected' : '' }}>{{ $caseType->description }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Judge Name</label>
                            <input type="text" name="judge_name" value="{{ old('judge_name', $case->judge_name ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Y.A. Dato' Ahmad">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Court Location</label>
                            <select name="court_name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select court location...</option>
                                @if(isset($case) && !empty($case->court_location))
                                    <option value="{{ $case->court_location }}" selected>{{ $case->court_location }}</option>
                                @endif
                                
                                <!-- All -->
                                <optgroup label="All">
                                    <option value="Pejabat Tanah" {{ old('court_name', $case->court_location ?? '') == 'Pejabat Tanah' ? 'selected' : '' }}>Pejabat Tanah</option>
                                </optgroup>
                                
                                <!-- Kuala Lumpur & Putrajaya -->
                                <optgroup label="Kuala Lumpur & Putrajaya">
                                    <option value="Dewan Bandaraya Kuala Lumpur" {{ old('court_name', $case->court_location ?? '') == 'Dewan Bandaraya Kuala Lumpur' ? 'selected' : '' }}>Dewan Bandaraya Kuala Lumpur</option>
                                    <option value="Kompleks Mahkamah Kuala Lumpur">Kompleks Mahkamah Kuala Lumpur</option>
                                    <option value="Kompleks Mahkamah Putrajaya">Kompleks Mahkamah Putrajaya</option>
                                </optgroup>

                                <!-- Selangor -->
                                <optgroup label="Selangor">
                                    <option value="Kompleks Sultan Salahuddin Abdul Aziz Shah">Kompleks Sultan Salahuddin Abdul Aziz Shah</option>
                                    <option value="Kompleks Mahkamah Ampang">Kompleks Mahkamah Ampang</option>
                                    <option value="Kompleks Mahkamah Petaling Jaya">Kompleks Mahkamah Petaling Jaya</option>
                                    <option value="Kompleks Mahkamah Selayang">Kompleks Mahkamah Selayang</option>
                                    <option value="Kompleks Mahkamah Kuala Kubu Bharu">Kompleks Mahkamah Kuala Kubu Bharu</option>
                                    <option value="Kompleks Mahkamah Sungai Besar">Kompleks Mahkamah Sungai Besar</option>
                                    <option value="Kompleks Mahkamah Telok Datok">Kompleks Mahkamah Telok Datok</option>
                                    <option value="Kompleks Mahkamah Kajang">Kompleks Mahkamah Kajang</option>
                                    <option value="Kompleks Mahkamah Bandar Baru Bangi">Kompleks Mahkamah Bandar Baru Bangi</option>
                                    <option value="Kompleks Mahkamah Kuala Selangor">Kompleks Mahkamah Kuala Selangor</option>
                                    <option value="Kompleks Mahkamah Sepang">Kompleks Mahkamah Sepang</option>
                                    <option value="Kompleks Mahkamah Klang">Kompleks Mahkamah Klang</option>
                                </optgroup>

                                <!-- Perak -->
                                <optgroup label="Perak">
                                    <option value="Kompleks Mahkamah Ipoh">Kompleks Mahkamah Ipoh</option>
                                    <option value="Kompleks Mahkamah Taiping">Kompleks Mahkamah Taiping</option>
                                    <option value="Kompleks Mahkamah Teluk Intan">Kompleks Mahkamah Teluk Intan</option>
                                    <option value="Kompleks Mahkamah Batu Gajah">Kompleks Mahkamah Batu Gajah</option>
                                    <option value="Kompleks Mahkamah Sg. Siput">Kompleks Mahkamah Sg. Siput</option>
                                    <option value="Kompleks Mahkamah Kuala Kangsar">Kompleks Mahkamah Kuala Kangsar</option>
                                    <option value="Kompleks Mahkamah Seri Manjung">Kompleks Mahkamah Seri Manjung</option>
                                    <option value="Kompleks Mahkamah Slim River">Kompleks Mahkamah Slim River</option>
                                    <option value="Kompleks Mahkamah Parit Buntar">Kompleks Mahkamah Parit Buntar</option>
                                    <option value="Kompleks Mahkamah Pantai Remis">Kompleks Mahkamah Pantai Remis</option>
                                    <option value="Kompleks Mahkamah Tanjung Malim">Kompleks Mahkamah Tanjung Malim</option>
                                    <option value="Kompleks Mahkamah Selama">Kompleks Mahkamah Selama</option>
                                    <option value="Kompleks Mahkamah Pengkalan Hulu">Kompleks Mahkamah Pengkalan Hulu</option>
                                    <option value="Kompleks Mahkamah Lenggong">Kompleks Mahkamah Lenggong</option>
                                </optgroup>

                                <!-- Pulau Pinang -->
                                <optgroup label="Pulau Pinang">
                                    <option value="Kompleks Mahkamah Pulau Pinang">Kompleks Mahkamah Pulau Pinang</option>
                                    <option value="Kompleks Mahkamah Butterworth">Kompleks Mahkamah Butterworth</option>
                                    <option value="Kompleks Mahkamah Bukit Mertajam">Kompleks Mahkamah Bukit Mertajam</option>
                                    <option value="Kompleks Mahkamah Balik Pulau">Kompleks Mahkamah Balik Pulau</option>
                                    <option value="Kompleks Mahkamah Jawi">Kompleks Mahkamah Jawi</option>
                                </optgroup>

                                <!-- Kedah -->
                                <optgroup label="Kedah">
                                    <option value="Kompleks Mahkamah Kangar">Kompleks Mahkamah Kangar</option>
                                    <option value="Kompleks Mahkamah Alor Setar">Kompleks Mahkamah Alor Setar</option>
                                    <option value="Kompleks Mahkamah Sungai Petani">Kompleks Mahkamah Sungai Petani</option>
                                    <option value="Kompleks Mahkamah Jitra">Kompleks Mahkamah Jitra</option>
                                    <option value="Kompleks Mahkamah Langkawi">Kompleks Mahkamah Langkawi</option>
                                    <option value="Kompleks Mahkamah Gurun">Kompleks Mahkamah Gurun</option>
                                    <option value="Kompleks Mahkamah Kulim">Kompleks Mahkamah Kulim</option>
                                    <option value="Kompleks Mahkamah Baling">Kompleks Mahkamah Baling</option>
                                    <option value="Kompleks Mahkamah Yan">Kompleks Mahkamah Yan</option>
                                    <option value="Kompleks Mahkamah Kuala Nerang">Kompleks Mahkamah Kuala Nerang</option>
                                    <option value="Kompleks Mahkamah Sik">Kompleks Mahkamah Sik</option>
                                    <option value="Kompleks Mahkamah Bandar Baharu">Kompleks Mahkamah Bandar Baharu</option>
                                </optgroup>

                                <!-- Negeri Sembilan -->
                                <optgroup label="Negeri Sembilan">
                                    <option value="Kompleks Mahkamah Alor Gajah">Kompleks Mahkamah Alor Gajah</option>
                                    <option value="Kompleks Mahkamah Seremban">Kompleks Mahkamah Seremban</option>
                                    <option value="Kompleks Mahkamah Kuala Pilah">Kompleks Mahkamah Kuala Pilah</option>
                                    <option value="Kompleks Mahkamah Tampin">Kompleks Mahkamah Tampin</option>
                                    <option value="Kompleks Mahkamah Port Dickson">Kompleks Mahkamah Port Dickson</option>
                                    <option value="Kompleks Mahkamah Bahau">Kompleks Mahkamah Bahau</option>
                                    <option value="Kompleks Mahkamah Rembau">Kompleks Mahkamah Rembau</option>
                                    <option value="Kompleks Mahkamah Gemas">Kompleks Mahkamah Gemas</option>
                                    <option value="Kompleks Mahkamah Jelebu">Kompleks Mahkamah Jelebu</option>
                                </optgroup>

                                <!-- Melaka -->
                                <optgroup label="Melaka">
                                    <option value="Kompleks Mahkamah Melaka">Kompleks Mahkamah Melaka</option>
                                    <option value="Kompleks Mahkamah Jasin">Kompleks Mahkamah Jasin</option>
                                </optgroup>

                                <!-- Johor -->
                                <optgroup label="Johor">
                                    <option value="Kompleks Mahkamah Batu Pahat">Kompleks Mahkamah Batu Pahat</option>
                                    <option value="Kompleks Mahkamah Muar">Kompleks Mahkamah Muar</option>
                                    <option value="Kompleks Mahkamah Segamat">Kompleks Mahkamah Segamat</option>
                                    <option value="Kompleks Mahkamah Tangkak">Kompleks Mahkamah Tangkak</option>
                                    <option value="Kompleks Mahkamah Kulai">Kompleks Mahkamah Kulai</option>
                                    <option value="Kompleks Mahkamah Kluang">Kompleks Mahkamah Kluang</option>
                                    <option value="Kompleks Mahkamah Kota Tinggi">Kompleks Mahkamah Kota Tinggi</option>
                                    <option value="Kompleks Mahkamah Pontian">Kompleks Mahkamah Pontian</option>
                                    <option value="Kompleks Mahkamah Mersing">Kompleks Mahkamah Mersing</option>
                                    <option value="Kompleks Mahkamah Yong Peng">Kompleks Mahkamah Yong Peng</option>
                                    <option value="Mahkamah Johor Bahru">Mahkamah Johor Bahru</option>
                                    <option value="Mahkamah Majistret (Majlis Bandaraya) Johor Bahru">Mahkamah Majistret (Majlis Bandaraya) Johor Bahru</option>
                                    <option value="Mahkamah Sesyen Khas PATI Ajil">Mahkamah Sesyen Khas PATI Ajil</option>
                                    <option value="Mahkamah Sesyen Khas PATI Lenggeng">Mahkamah Sesyen Khas PATI Lenggeng</option>
                                    <option value="Mahkamah Sesyen Khas PATI Machap Umboo">Mahkamah Sesyen Khas PATI Machap Umboo</option>
                                    <option value="Mahkamah Sesyen Khas PATI Pekan Nanas">Mahkamah Sesyen Khas PATI Pekan Nanas</option>
                                    <option value="Mahkamah Sesyen Khas PATI Semenyih">Mahkamah Sesyen Khas PATI Semenyih</option>
                                </optgroup>

                                <!-- Pahang -->
                                <optgroup label="Pahang">
                                    <option value="Kompleks Mahkamah Kuantan">Kompleks Mahkamah Kuantan</option>
                                    <option value="Kompleks Mahkamah Temerloh">Kompleks Mahkamah Temerloh</option>
                                    <option value="Kompleks Mahkamah Raub">Kompleks Mahkamah Raub</option>
                                    <option value="Kompleks Mahkamah Pekan">Kompleks Mahkamah Pekan</option>
                                    <option value="Kompleks Mahkamah Maran">Kompleks Mahkamah Maran</option>
                                    <option value="Kompleks Mahkamah Bentong">Kompleks Mahkamah Bentong</option>
                                    <option value="Kompleks Mahkamah Cameron Highlands">Kompleks Mahkamah Cameron Highlands</option>
                                    <option value="Kompleks Mahkamah Rompin">Kompleks Mahkamah Rompin</option>
                                    <option value="Kompleks Mahkamah Kuala Lipis">Kompleks Mahkamah Kuala Lipis</option>
                                    <option value="Kompleks Mahkamah Jerantut">Kompleks Mahkamah Jerantut</option>
                                </optgroup>

                                <!-- Terengganu -->
                                <optgroup label="Terengganu">
                                    <option value="Kompleks Mahkamah Kuala Terengganu">Kompleks Mahkamah Kuala Terengganu</option>
                                    <option value="Kompleks Mahkamah Kemaman">Kompleks Mahkamah Kemaman</option>
                                    <option value="Kompleks Mahkamah Dungun">Kompleks Mahkamah Dungun</option>
                                    <option value="Kompleks Mahkamah Besut">Kompleks Mahkamah Besut</option>
                                    <option value="Kompleks Mahkamah Kuala Berang">Kompleks Mahkamah Kuala Berang</option>
                                    <option value="Kompleks Mahkamah Marang">Kompleks Mahkamah Marang</option>
                                    <option value="Kompleks Mahkamah Setiu">Kompleks Mahkamah Setiu</option>
                                </optgroup>

                                <!-- Kelantan -->
                                <optgroup label="Kelantan">
                                    <option value="Kompleks Mahkamah Kota Bharu">Kompleks Mahkamah Kota Bharu</option>
                                    <option value="Kompleks Mahkamah Tumpat">Kompleks Mahkamah Tumpat</option>
                                    <option value="Kompleks Mahkamah Tanah Merah">Kompleks Mahkamah Tanah Merah</option>
                                    <option value="Kompleks Mahkamah Bachok">Kompleks Mahkamah Bachok</option>
                                    <option value="Kompleks Mahkamah Jeli">Kompleks Mahkamah Jeli</option>
                                    <option value="Kompleks Mahkamah Pasir Puteh">Kompleks Mahkamah Pasir Puteh</option>
                                    <option value="Kompleks Mahkamah Kuala Krai">Kompleks Mahkamah Kuala Krai</option>
                                    <option value="Kompleks Mahkamah Machang">Kompleks Mahkamah Machang</option>
                                    <option value="Kompleks Mahkamah Gua Musang">Kompleks Mahkamah Gua Musang</option>
                                    <option value="Kompleks Mahkamah Pasir Mas">Kompleks Mahkamah Pasir Mas</option>
                                </optgroup>

                                <!-- Sabah & Sarawak -->
                                <optgroup label="Sabah & Sarawak">
                                    <option value="Kompleks Mahkamah Pengerang">Kompleks Mahkamah Pengerang</option>
                                </optgroup>

                                <!-- Istana Kehakiman -->
                                <optgroup label="Istana Kehakiman">
                                    <option value="Istana Kehakiman">Istana Kehakiman</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Jurisdiction</label>
                            <select name="jurisdiction" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @php $jur = old('jurisdiction', $case->jurisdiction ?? ''); @endphp
                                <option value="">Select jurisdiction...</option>
                                <option value="mahkamah_persekutuan" {{ $jur=='mahkamah_persekutuan' ? 'selected' : '' }}>Mahkamah Persekutuan</option>
                                <option value="mahkamah_rayuan" {{ $jur=='mahkamah_rayuan' ? 'selected' : '' }}>Mahkamah Rayuan</option>
                                <option value="mahkamah_tinggi" {{ $jur=='mahkamah_tinggi' ? 'selected' : '' }}>Mahkamah Tinggi</option>
                                <option value="mahkamah_sesyen" {{ $jur=='mahkamah_sesyen' ? 'selected' : '' }}>Mahkamah Sesyen</option>
                                <option value="mahkamah_majistret" {{ $jur=='mahkamah_majistret' ? 'selected' : '' }}>Mahkamah Majistret</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Section</label>
                            <select id="section-select" name="section" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @php 
                                    $sec = old('section', $case->section ?? ''); 
                                    if (!$sec && isset($case)) {
                                        $desc = strtolower($case->caseType->description ?? '');
                                        if (str_contains($desc, 'criminal')) { $sec = 'criminal'; }
                                        elseif (str_contains($desc, 'civil')) { $sec = 'civil'; }
                                        elseif (str_contains($desc, 'convey')) { $sec = 'conveyancing'; }
                                        elseif (str_contains($desc, 'probate') || str_contains($desc,'administration')) { $sec = 'probate'; }
                                    }
                                @endphp
                                <option value="">Select section...</option>
                                <option value="civil" {{ $sec=='civil' ? 'selected' : '' }}>Civil</option>
                                <option value="criminal" {{ $sec=='criminal' ? 'selected' : '' }}>Criminal</option>
                                <option value="conveyancing" {{ $sec=='conveyancing' ? 'selected' : '' }}>Conveyancing</option>
                                <option value="probate" {{ $sec=='probate' ? 'selected' : '' }}>Probate / Letter of Administration</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Initiating Documents</label>
                            <select id="documents-select" name="initiating_document" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="">Please select a section first...</option>
                            </select>
                        </div>
                        <!-- Others Document Field (only when Probate section and "Others" is selected in Case Initiating Documents) -->
                        <div id="others-document-field" style="display: none;">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Others</label>
                            <input type="text" name="others_document" value="{{ old('others_document', $case->others_document ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Specify other document">
                        </div>
                        <!-- Name of Property Field (only for Conveyancing) -->
                        <div id="property-name-field" style="display: none;">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Name of Property</label>
                            <input type="text" name="name_of_property" value="{{ old('name_of_property', $case->name_of_property ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter property name">
                        </div>
                        <div id="claim-amount-field">
                            <label class="block text-xs font-medium text-gray-700 mb-2" id="claim-amount-label">Total Claim (RM) *</label>
                            <input type="number" name="claim_amount" step="0.01" x-model="totalClaim" value="{{ old('claim_amount', $case->claim_amount ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required>
                        </div>
                        <div id="case-title-field">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Title *</label>
                            <input type="text" name="case_title" value="{{ old('case_title', $case->title ?? '') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter case title" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Description</label>
                            <textarea name="case_description" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Enter case description">{{ old('case_description', $case->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Client Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">people</span>
                        Client Information
                    </h3>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs text-gray-600 ml-6">Add applicants and respondents to this case</p>
                        <div class="flex gap-2">
                            <button type="button" @click="showPlaintiffDropdown = !showPlaintiffDropdown" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-sm text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add</span>
                                Add Applicant (<span x-text="applicantList.length"></span> available)
                            </button>
                            <button type="button" @click="showDefendantDropdown = !showDefendantDropdown" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-sm text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add</span>
                                Add Respondent (<span x-text="respondentList.length"></span> available)
                            </button>
                        </div>
                    </div>
                    
                    <!-- Plaintiff Selection Dropdown -->
                    <div x-show="showPlaintiffDropdown" x-transition class="mb-4">
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <h4 class="text-xs font-medium text-gray-700 mb-3">Select Applicant</h4>
                            <div x-show="applicantList.length === 0" class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
                                No applicants available. Please add clients with party type 'applicant' first.
                            </div>
                            <select x-model="selectedPlaintiff" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" :disabled="applicantList.length === 0">
                                <option value="">Choose applicant from client list...</option>
                                <template x-for="client in applicantList" :key="client.id">
                                    <option :value="client.id" x-text="client.name + ' - ' + client.ic"></option>
                                </template>
                            </select>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="showPlaintiffDropdown = false" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" @click="addSelectedPlaintiff()" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700" :disabled="applicantList.length === 0">
                                    Add Applicant
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Defendant Selection Dropdown -->
                    <div x-show="showDefendantDropdown" x-transition class="mb-4">
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <h4 class="text-xs font-medium text-gray-700 mb-3">Select Respondent</h4>
                            <div x-show="respondentList.length === 0" class="mb-3 p-3 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
                                No respondents available. Please add clients with party type 'respondent' first.
                            </div>
                            <select x-model="selectedDefendant" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" :disabled="respondentList.length === 0">
                                <option value="">Choose respondent from client list...</option>
                                <template x-for="client in respondentList" :key="client.id">
                                    <option :value="client.id" x-text="client.name + ' - ' + client.ic"></option>
                                </template>
                            </select>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="showDefendantDropdown = false" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" @click="addSelectedDefendant()" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700" :disabled="respondentList.length === 0">
                                    Add Respondent
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Plaintiffs Information (within Client Information Section) -->
                    <div x-show="plaintiffs.length > 0" x-transition class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <span class="material-icons text-green-600 text-base mr-2">person</span>
                            Applicant Information
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(plaintiff, index) in plaintiffs" :key="index">
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Applicant <span x-text="index + 1"></span></span>
                                        <button type="button" @click="removePlaintiff(index)" class="text-red-600 hover:text-red-800 text-xs">
                                            <span class="material-icons text-xs">remove_circle</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Client Details Section -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                                            <input type="text" x-model="plaintiff.name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Full name" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">IC/Passport *</label>
                                            <input type="text" x-model="plaintiff.ic" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="IC or passport number" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                                            <input type="text" x-model="plaintiff.gender" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Gender" readonly>
                                        </div>
                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nationality</label>
                                            <input type="text" x-model="plaintiff.nationality" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Nationality" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Mobile Number</label>
                                            <input type="text" x-model="plaintiff.phone" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Mobile number" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                                            <input type="email" x-model="plaintiff.email" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Email address" readonly>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Defendants Information (within Client Information Section) -->
                    <div x-show="defendants.length > 0" x-transition class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <span class="material-icons text-red-600 text-base mr-2">person</span>
                            Respondent Information
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(defendant, index) in defendants" :key="index">
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Respondent <span x-text="index + 1"></span></span>
                                        <button type="button" @click="removeDefendant(index)" class="text-red-600 hover:text-red-800 text-xs">
                                            <span class="material-icons text-xs">remove_circle</span>
                                        </button>
                                    </div>
                                    
                                    <!-- Client Details Section -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Full Name</label>
                                            <input type="text" x-model="defendant.name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Full name" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">IC/Passport *</label>
                                            <input type="text" x-model="defendant.ic" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="IC or passport number" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Gender</label>
                                            <input type="text" x-model="defendant.gender" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Gender" readonly>
                        </div>
                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Nationality</label>
                                            <input type="text" x-model="defendant.nationality" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Nationality" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Mobile Number</label>
                                            <input type="text" x-model="defendant.phone" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Mobile number" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Email Address</label>
                                            <input type="email" x-model="defendant.email" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="Email address" readonly>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Partner In Charge Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-orange-600 text-base mr-2">business</span>
                        Partner In Charge *
                    </h3>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-gray-600 ml-6">Assign partners to handle this case</p>
                        <button type="button" @click="showPartnerDropdown = !showPartnerDropdown" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-sm text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">add</span>
                            Add Partner
                        </button>
                    </div>
                    
                    <!-- Partner Selection Dropdown -->
                    <div x-show="showPartnerDropdown" x-transition class="mb-4">
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <h4 class="text-xs font-medium text-gray-700 mb-3">Select Partner</h4>
                            <select x-model="selectedPartner" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Choose partner from list...</option>
                                <template x-for="partner in partnerList" :key="partner.id">
                                    <option :value="partner.id" x-text="partner.name + ' - ' + partner.specialization"></option>
                                </template>
                            </select>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="showPartnerDropdown = false" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" @click="addSelectedPartner()" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Add Partner
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Partners Information (within Partner In Charge Section) -->
                    <div x-show="partners.length > 0" x-transition class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <span class="material-icons text-orange-600 text-base mr-2">person</span>
                            Partners In Charge
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(partner, index) in partners" :key="index">
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Partner <span x-text="index + 1"></span></span>
                                        <button type="button" @click="removePartner(index)" class="text-red-600 hover:text-red-800 text-xs">
                                            <span class="material-icons text-xs">remove_circle</span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Firm Name</label>
                                            <input type="text" x-model="partner.firm_name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="e.g., Firm Name" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" x-model="partner.email" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="e.g., info@firmname.com" readonly>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                                            <input type="text" x-model="partner.contact" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="e.g., +60 1234 567890" readonly>
                        </div>
                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Specialization</label>
                                            <input type="text" x-model="partner.specialization" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 bg-gray-100" placeholder="e.g., Civil Law" readonly>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Warrant to Act Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-blue-600 text-base mr-2">gavel</span>
                        Warrant to Act Configuration
                    </h3>
                    <p class="text-xs text-gray-600 ml-6 mb-4">Configure automatic Warrant to Act generation</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Generate Warrant to Act -->
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Generate Warrant to Act</label>
                            <select name="generate_wta" id="generate-wta" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="1" selected>Yes, Generate Automatically</option>
                                <option value="0">No, Skip Generation</option>
                            </select>
                        </div>

                        <!-- Select Party for WTA -->
                        <div id="wta-party-selection">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Select Party for Warrant to Act</label>
                            <select name="wta_party_type" id="wta-party-type" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="applicant" selected>First Applicant/Plaintiff</option>
                                <option value="respondent">First Respondent/Defendant</option>
                                <option value="custom">Select Specific Party</option>
                            </select>
                        </div>
                    </div>

                    <!-- Custom Party Selection (Hidden by default) -->
                    <div id="custom-party-selection" class="mt-4" style="display: none;">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Select Specific Party</label>
                        <select name="wta_specific_party" id="wta-specific-party" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a party...</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Add parties above first, then select specific party here</p>
                    </div>

                    <!-- WTA Preview Info -->
                    <div id="wta-preview" class="mt-4 p-3 bg-white rounded border border-blue-200">
                        <div class="flex items-center mb-2">
                            <span class="material-icons text-blue-600 text-sm mr-2">info</span>
                            <span class="text-xs font-medium text-gray-700">Warrant to Act Preview</span>
                        </div>
                        <div id="wta-preview-content" class="text-xs text-gray-600">
                            <p><strong>Party:</strong> <span id="preview-party">First Applicant/Plaintiff</span></p>
                            <p><strong>Status:</strong> <span id="preview-status" class="text-green-600">Will be generated automatically</span></p>
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Documents Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-purple-600 text-base mr-2">folder</span>
                        Documents
                    </h3>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs text-gray-600 ml-6">Upload and manage case documents</p>
                        <button type="button" @click="addDocument()" class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1.5 rounded-sm text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">add</span>
                            Add Document
                        </button>
                    </div>

                    <!-- Documents Table -->
                    <div class="bg-white rounded border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">File Upload</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Type of Documents</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">File By</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Date Filing</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">Status</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-700">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <template x-for="(document, index) in documents" :key="index">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 align-middle">
                                                <template x-if="document.existing">
                                                    <div class="text-xs text-gray-700">
                                                        <p class="font-medium" x-text="document.file_name"></p>
                                                        <p class="text-gray-500" x-text="document.file_size"></p>
                                                    </div>
                                                </template>
                                                <template x-if="!document.existing">
                                                    <div class="space-y-2">
                                                        <input type="file" @change="handleFileUpload($event, index)" class="w-full px-2 py-1 border border-gray-300 rounded text-xs" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                                        <div x-show="document.file" class="text-xs text-gray-600">
                                                            <p><strong>File:</strong> <span x-text="document.file_name"></span></p>
                                                            <p><strong>Size:</strong> <span x-text="document.file_size"></span></p>
                                                        </div>
                                                    </div>
                                                </template>
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <select x-model="document.type" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                                    <option value="">Select type...</option>
                                                    @foreach($fileTypes as $fileType)
                                                        <option value="{{ $fileType->id }}">{{ $fileType->description }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <select x-model="document.filed_by" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                                    <option value="">Select firm...</option>
                                                    @foreach($partners as $p)
                                                        <option value="{{ $p->firm_name }}">{{ $p->firm_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <input type="date" x-model="document.filing_date" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                            </td>
                                            <td class="px-3 py-2 align-middle">
                                                <select x-model="document.status" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                                    <option value="">Select status...</option>
                                                    @foreach($categoryStatuses as $st)
                                                        <option value="{{ strtolower($st->name) }}">{{ $st->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 text-center align-middle relative">
                                                <template x-if="document.existing">
                                                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 inline-flex items-center justify-center gap-2">
                                                        <a :href="'/file-management/'+document.id+'/download'" class="flex items-center justify-center w-6 h-6 text-blue-600 hover:text-blue-800" title="Download">
                                                            <span class="material-icons text-[18px] leading-none" style="vertical-align: middle !important; line-height: 1 !important;">download</span>
                                                        </a>
                                                        <form :action="'/file-management/'+document.id" method="POST" class="flex items-center justify-center" @submit.prevent="if(confirm('Delete this file?')) $el.submit()">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="flex items-center justify-center w-6 h-6 text-red-600 hover:text-red-800" title="Delete">
                                                                <span class="material-icons text-[18px] leading-none" style="vertical-align: middle !important; line-height: 1 !important;">delete</span>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </template>
                                                <template x-if="!document.existing">
                                                <button type="button" @click="removeDocument(index)" class="text-red-600 hover:text-red-800 text-xs">
                                                    <span class="material-icons text-base">delete</span>
                                                </button>
                                                </template>
                                            </td>
                                        </tr>
                                    </template>
                                    <tr x-show="documents.length === 0">
                                        <td colspan="6" class="px-3 py-4 text-center text-xs text-gray-500">
                                            No documents added yet. Click "Add Document" to add one.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Financial Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">attach_money</span>
                        Financial Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Summary of invoice and receipt totals</p>
                    @if(isset($case))
                    @php
                        $totalInvoiced = optional($case->taxInvoices)->sum('total') ?? 0;
                        $totalPaid = optional($case->receipts)->sum('amount_paid') ?? 0;
                        $balance = max(0, $totalInvoiced - $totalPaid);
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 rounded p-3">
                            <div class="text-[10px] text-gray-500">Total Amount Invoice</div>
                            <div class="text-sm font-semibold text-gray-900">RM {{ number_format($totalInvoiced, 2) }}</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded p-3">
                            <div class="text-[10px] text-gray-500">Amount Paid</div>
                            <div class="text-sm font-semibold text-green-700">RM {{ number_format($totalPaid, 2) }}</div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded p-3">
                            <div class="text-[10px] text-gray-500">Balance</div>
                            <div class="text-sm font-semibold {{ $balance>0 ? 'text-red-700' : 'text-green-700' }}">RM {{ number_format($balance, 2) }}</div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Additional Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-blue-600 text-base mr-2">info</span>
                        Additional Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Set priority level and case status</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Priority Level</label>
                            <select name="priority_level" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @php $prio = old('priority_level', $case->priority_level ?? 'medium'); @endphp
                                <option value="low" {{ $prio=='low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ $prio=='medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ $prio=='high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ $prio=='urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select name="case_status_id" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select status...</option>
                                @foreach($caseStatuses as $caseStatus)
                                    <option value="{{ $caseStatus->id }}" {{ old('case_status_id', $case->case_status_id ?? null) == $caseStatus->id ? 'selected' : '' }}>{{ $caseStatus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4">
                    <a href="{{ route('case.index') }}" class="w-full md:w-auto px-3 py-1.5 text-gray-600 border border-gray-300 rounded-sm text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button type="submit" class="w-full md:w-auto px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700">
                        {{ isset($case) ? 'Update Case' : 'Create Case' }}
                    </button>
                </div>

                <!-- Hidden inputs for form data -->
                <!-- Hidden inputs will be generated by JavaScript when form submits -->
                <div id="hidden-inputs-container"></div>
            </form>
        </div>
    </div>
</div>
@endsection 

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section-select');
        const documentsSelect = document.getElementById('documents-select');
        const courtLocationSelect = document.querySelector('select[name="court_name"]');
        const claimAmountField = document.getElementById('claim-amount-field');
        const claimAmountInput = claimAmountField ? claimAmountField.querySelector('input[name="claim_amount"]') : null;
        const savedClaimAmount = claimAmountInput ? claimAmountInput.value : '';
        
        const documentOptions = {
            civil: [
                { value: 'originating_summons', text: 'Originating Summons' },
                { value: 'judgment_debtor_summons', text: 'Judgment Debtor Summons' },
                { value: 'writ_of_execution', text: 'Writ of Execution' },
                { value: 'writ_of_summons', text: 'Writ of Summons' },
                { value: 'citizenship_investigation_report', text: 'Citizenship Investigation Report' }
            ],
            criminal: [
                { value: 'charge_sheet_alternative', text: 'Charge Sheet / Alternative Charge' },
                { value: 'criminal_application_motion', text: 'Criminal Application / Motion' },
                { value: 'summons_charge_sheet', text: 'Summons and Charge Sheet / Alternative Charge' },
                { value: 'death_investigation_report', text: 'Death Investigation Report (Coroner)' }
            ],
            conveyancing: [
                { value: 'letter_offer', text: 'Letter Offer' },
                { value: 'sale_purchase_agreement', text: 'Sale & Purchase Agreement' },
                { value: 'loan_agreement', text: 'Loan Agreement/ Facility Agreement' }
            ],
            probate: [
                { value: 'originating_summons_exparte', text: 'Originating Summons (Ex-Parte)' },
                { value: 'form_p', text: 'Form P' },
                { value: 'others', text: 'Others' }
            ]
        };
        
        function populateDocumentsForSection(selectedSection, preselectValue) {
            documentsSelect.innerHTML = '<option value="">Select document...</option>';
            if (selectedSection && documentOptions[selectedSection]) {
                documentsSelect.disabled = false;
                documentsSelect.removeAttribute('disabled');
                documentOptions[selectedSection].forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.text;
                    if (preselectValue && String(preselectValue) === String(option.value)) {
                        optionElement.selected = true;
                    }
                    documentsSelect.appendChild(optionElement);
                });
                if (preselectValue) {
                    documentsSelect.value = preselectValue;
                }
            } else {
                documentsSelect.disabled = true;
                documentsSelect.innerHTML = '<option value="">Please select a section first...</option>';
            }
        }

        sectionSelect.addEventListener('change', function() {
            const selectedSection = this.value;
            const caseTitleField = document.getElementById('case-title-field');
            const caseTitleInput = caseTitleField ? caseTitleField.querySelector('input[name="case_title"]') : null;
            const propertyNameField = document.getElementById('property-name-field');
            const othersDocumentField = document.getElementById('others-document-field');
            const claimAmountLabel = document.getElementById('claim-amount-label');

            // Handle Conveyancing specific logic
            if (selectedSection === 'conveyancing') {
                // Hide Case Title field for Conveyancing
                if (caseTitleField) {
                    caseTitleField.style.display = 'none';
                    if (caseTitleInput) {
                        caseTitleInput.removeAttribute('required');
                        caseTitleInput.value = '';
                    }
                }
                // Show Name of Property field
                if (propertyNameField) {
                    propertyNameField.style.display = 'block';
                }
                // Change label to Purchase Price
                if (claimAmountLabel) {
                    claimAmountLabel.textContent = 'Purchase Price *';
                }
                // Show claim amount field
                claimAmountField.style.display = 'block';
                claimAmountInput.setAttribute('required', 'required');
                if (!claimAmountInput.value && savedClaimAmount) {
                    claimAmountInput.value = savedClaimAmount;
                }
            } else {
                // Show Case Title field for non-Conveyancing
                if (caseTitleField) {
                    caseTitleField.style.display = 'block';
                    if (caseTitleInput) {
                        caseTitleInput.setAttribute('required', 'required');
                    }
                }
                // Hide Name of Property field
                if (propertyNameField) {
                    propertyNameField.style.display = 'none';
                }
                // Hide Others Document field for non-Probate
                if (othersDocumentField) {
                    othersDocumentField.style.display = 'none';
                    const othersInput = othersDocumentField.querySelector('input[name="others_document"]');
                    if (othersInput) {
                        othersInput.value = '';
                    }
                }
                // Change label back to Total Claim
                if (claimAmountLabel) {
                    claimAmountLabel.textContent = 'Total Claim (RM) *';
                }

                // Hide/show Total Claim field based on section
                if (selectedSection === 'criminal') {
                    claimAmountField.style.display = 'none';
                    claimAmountInput.removeAttribute('required');
                    claimAmountInput.value = '';
                } else {
                    claimAmountField.style.display = 'block';
                    claimAmountInput.setAttribute('required', 'required');
                    // If value was cleared earlier, restore saved amount
                    if (!claimAmountInput.value && savedClaimAmount) {
                        claimAmountInput.value = savedClaimAmount;
                    }
                }
            }

            // Populate docs for chosen section
            const savedDoc = @json(isset($case) ? ($case->initiating_document ?? null) : null);
            populateDocumentsForSection(selectedSection, savedDoc);
        });

        // Add event listener for Case Initiating Documents dropdown
        documentsSelect.addEventListener('change', function() {
            const selectedDocument = this.value;
            const selectedSection = sectionSelect.value;
            const othersDocumentField = document.getElementById('others-document-field');

            // Show/hide Others Document field ONLY for Probate section AND Others document
            if (selectedSection === 'probate' && selectedDocument === 'others') {
                if (othersDocumentField) {
                    othersDocumentField.style.display = 'block';
                }
            } else {
                if (othersDocumentField) {
                    othersDocumentField.style.display = 'none';
                    // Clear the others document field when hiding
                    const othersInput = othersDocumentField.querySelector('input[name="others_document"]');
                    if (othersInput) {
                        othersInput.value = '';
                    }
                }
            }
        });

        // Prefill for edit mode: court location and section
        try {
            const existing = {!! isset($case)
                ? json_encode([
                    'court_location' => $case->court_location ?? null,
                    'case_type' => optional($case->caseType)->description,
                    'section' => $case->section ?? null,
                    'initiating_document' => $case->initiating_document ?? null,
                ])
                : 'null' !!};

            if (existing) {
                // Court Location
                if (existing.court_location && courtLocationSelect) {
                    courtLocationSelect.value = existing.court_location;
                }

                // Section from case type description
                if ((existing.section || existing.case_type) && sectionSelect) {
                    const type = (existing.case_type || '').toLowerCase();
                    let sectionValue = existing.section || '';
                    if (!sectionValue) {
                        if (type.includes('criminal')) sectionValue = 'criminal';
                        else if (type.includes('civil')) sectionValue = 'civil';
                        else if (type.includes('convey')) sectionValue = 'conveyancing';
                        else if (type.includes('probate') || type.includes('administration')) sectionValue = 'probate';
                    }

                    if (sectionValue) {
                        sectionSelect.value = sectionValue;
                        // Immediately populate docs and preselect saved initiating doc
                        populateDocumentsForSection(sectionValue, existing.initiating_document);
                        // Trigger the change event to apply Conveyancing logic
                        sectionSelect.dispatchEvent(new Event('change'));
                        // Trigger documents change event to show Others field if needed
                        setTimeout(() => {
                            if (existing.initiating_document) {
                                documentsSelect.value = existing.initiating_document;
                                documentsSelect.dispatchEvent(new Event('change'));
                            }
                        }, 100);
                    }
                }
            }
        } catch (e) {
            // Prefill init failed - silently continue
        }
        // Fallback: ensure documents are populated on load even if no event fired
        const initialSection = sectionSelect ? sectionSelect.value : '';
        if (initialSection) {
            const savedDocFinal = @json(isset($case) ? ($case->initiating_document ?? null) : null);
            populateDocumentsForSection(initialSection, savedDocFinal);
            // Trigger the change event to apply section-specific logic (including Conveyancing)
            sectionSelect.dispatchEvent(new Event('change'));
            // Trigger documents change event to show Others field if needed
            setTimeout(() => {
                if (savedDocFinal) {
                    documentsSelect.value = savedDocFinal;
                    documentsSelect.dispatchEvent(new Event('change'));
                }
            }, 100);
            // Ensure claim amount visible and restored for non-criminal
            if (initialSection !== 'criminal' && claimAmountField && claimAmountInput && !claimAmountInput.value && savedClaimAmount) {
                claimAmountInput.value = savedClaimAmount;
            }
        }

        // Warrant to Act Configuration
        const generateWtaSelect = document.getElementById('generate-wta');
        const wtaPartyTypeSelect = document.getElementById('wta-party-type');
        const customPartySelection = document.getElementById('custom-party-selection');
        const wtaSpecificPartySelect = document.getElementById('wta-specific-party');
        const previewParty = document.getElementById('preview-party');
        const previewStatus = document.getElementById('preview-status');

        function updateWtaPreview() {
            const generateWta = generateWtaSelect.value === '1';
            const partyType = wtaPartyTypeSelect.value;

            if (!generateWta) {
                previewStatus.textContent = 'Generation disabled';
                previewStatus.className = 'text-red-600';
                previewParty.textContent = 'None';
                return;
            }

            previewStatus.textContent = 'Will be generated automatically';
            previewStatus.className = 'text-green-600';

            switch (partyType) {
                case 'applicant':
                    previewParty.textContent = 'First Applicant/Plaintiff';
                    break;
                case 'respondent':
                    previewParty.textContent = 'First Respondent/Defendant';
                    break;
                case 'custom':
                    const selectedParty = wtaSpecificPartySelect.options[wtaSpecificPartySelect.selectedIndex];
                    previewParty.textContent = selectedParty.value ? selectedParty.text : 'Select specific party';
                    break;
            }
        }

        function updateCustomPartyOptions() {
            // Clear existing options
            wtaSpecificPartySelect.innerHTML = '<option value="">Select a party...</option>';

            // Get all parties from the form
            const applicantRows = document.querySelectorAll('#applicants-table tbody tr');
            const respondentRows = document.querySelectorAll('#respondents-table tbody tr');

            // Add applicants
            applicantRows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                if (nameInput && nameInput.value.trim()) {
                    const option = document.createElement('option');
                    option.value = `applicant_${index}`;
                    option.textContent = `Applicant: ${nameInput.value.trim()}`;
                    wtaSpecificPartySelect.appendChild(option);
                }
            });

            // Add respondents
            respondentRows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name*="[name]"]');
                if (nameInput && nameInput.value.trim()) {
                    const option = document.createElement('option');
                    option.value = `respondent_${index}`;
                    option.textContent = `Respondent: ${nameInput.value.trim()}`;
                    wtaSpecificPartySelect.appendChild(option);
                }
            });
        }

        // Event listeners for WTA configuration
        if (generateWtaSelect) {
            generateWtaSelect.addEventListener('change', updateWtaPreview);
        }

        if (wtaPartyTypeSelect) {
            wtaPartyTypeSelect.addEventListener('change', function() {
                const isCustom = this.value === 'custom';
                if (customPartySelection) {
                    customPartySelection.style.display = isCustom ? 'block' : 'none';
                }

                if (isCustom) {
                    updateCustomPartyOptions();
                }

                updateWtaPreview();
            });
        }

        if (wtaSpecificPartySelect) {
            wtaSpecificPartySelect.addEventListener('change', updateWtaPreview);
        }

        // Update custom party options when parties are added/removed
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && wtaPartyTypeSelect && wtaPartyTypeSelect.value === 'custom') {
                    updateCustomPartyOptions();
                    updateWtaPreview();
                }
            });
        });

        // Observe changes in parties tables
        const applicantsTable = document.getElementById('applicants-table');
        const respondentsTable = document.getElementById('respondents-table');

        if (applicantsTable) {
            observer.observe(applicantsTable, { childList: true, subtree: true });
        }
        if (respondentsTable) {
            observer.observe(respondentsTable, { childList: true, subtree: true });
        }

        // Initial preview update
        updateWtaPreview();
    });

    // Case form submission function
    window.submitCaseForm = function(formElement) {
        try {
            // Get Alpine.js data
            const alpineData = Alpine.$data(formElement);

            // Check required fields
            const caseRef = formElement.querySelector('input[name="case_ref"]').value;
            const personInCharge = formElement.querySelector('input[name="person_in_charge"]').value;
            const caseTypeId = formElement.querySelector('select[name="case_type_id"]').value;
            const caseStatusId = formElement.querySelector('select[name="case_status_id"]').value;
            const section = formElement.querySelector('select[name="section"]').value;

            // Conditional validation based on section
            let caseTitle = '';
            let nameOfProperty = '';

            if (section === 'conveyancing') {
                nameOfProperty = formElement.querySelector('input[name="name_of_property"]').value;
                if (!nameOfProperty) {
                    alert('Please fill in Name of Property for Conveyancing cases');
                    return;
                }
            } else {
                caseTitle = formElement.querySelector('input[name="case_title"]').value;
                if (!caseTitle) {
                    alert('Please fill in Case Title');
                    return;
                }
            }

            if (!caseRef || !personInCharge || !caseTypeId || !caseStatusId) {
                alert('Please fill in all required fields');
                return;
            }

            // Generate hidden inputs using innerHTML
            const container = document.getElementById('hidden-inputs-container');
            let hiddenInputsHTML = '';

            // Generate plaintiffs inputs
            if (alpineData.plaintiffs && alpineData.plaintiffs.length > 0) {
                alpineData.plaintiffs.forEach((plaintiff, index) => {
                    const fields = ['name', 'ic', 'phone', 'email', 'gender', 'nationality', 'client_id'];
                    fields.forEach(field => {
                        const rawValue = plaintiff[field] || '';
                        const value = String(rawValue).replace(/"/g, '&quot;');
                        hiddenInputsHTML += '<input type="hidden" name="plaintiffs[' + index + '][' + field + ']" value="' + value + '">';
                    });
                });
            }

            // Generate defendants inputs
            if (alpineData.defendants && alpineData.defendants.length > 0) {
                alpineData.defendants.forEach((defendant, index) => {
                    const fields = ['name', 'ic', 'phone', 'email', 'gender', 'nationality', 'client_id'];
                    fields.forEach(field => {
                        const rawValue = defendant[field] || '';
                        const value = String(rawValue).replace(/"/g, '&quot;');
                        hiddenInputsHTML += '<input type="hidden" name="defendants[' + index + '][' + field + ']" value="' + value + '">';
                    });
                });
            }

            // Generate partners inputs
            if (alpineData.partners && alpineData.partners.length > 0) {
                alpineData.partners.forEach((partner, index) => {
                    const fields = ['partner_id', 'role'];
                    fields.forEach(field => {
                        const rawValue = partner[field] || '';
                        const value = String(rawValue).replace(/"/g, '&quot;');
                        hiddenInputsHTML += '<input type="hidden" name="partners[' + index + '][' + field + ']" value="' + value + '">';
                    });
                });
            }

            // Generate documents inputs
            if (alpineData.documents && alpineData.documents.length > 0) {
                alpineData.documents.forEach((document, index) => {
                    const fields = ['type', 'filed_by', 'filing_date', 'status'];
                    fields.forEach(field => {
                        const rawValue = document[field] || '';
                        const value = String(rawValue).replace(/"/g, '&quot;');
                        hiddenInputsHTML += '<input type="hidden" name="documents[' + index + '][' + field + ']" value="' + value + '">';
                    });
                    // Skip file field for now as it needs special handling
                });
            }

            // Set the HTML
            container.innerHTML = hiddenInputsHTML;

            // Check if form is valid
            if (!formElement.checkValidity()) {
                formElement.reportValidity();
                return;
            }

            formElement.submit();

        } catch (error) {
            alert('Error submitting form: ' + error.message);
        }
    };
</script>