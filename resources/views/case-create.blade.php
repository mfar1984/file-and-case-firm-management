@extends('layouts.app')

@section('breadcrumb')
    Case > Add New Case
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-purple-600">add_circle</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Add New Case</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Create a new court case with all necessary details.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form class="space-y-0" x-data="{
                plaintiffs: [],
                defendants: [],
                partners: [],
                documents: [],
                totalClaim: 0,
                showPlaintiffDropdown: false,
                showDefendantDropdown: false,
                showPartnerDropdown: false,
                selectedPlaintiff: '',
                selectedDefendant: '',
                selectedPartner: '',
                clientList: [
                    { id: 1, name: 'John Doe', ic: '123456-78-901234' },
                    { id: 2, name: 'Jane Smith', ic: '987654-32-109876' },
                    { id: 3, name: 'Peter Jones', ic: '112233-44-556677' },
                    { id: 4, name: 'Mary Brown', ic: '998877-66-554433' },
                    { id: 5, name: 'David Lee', ic: '123123-45-678901' },
                    { id: 6, name: 'Sarah Chen', ic: '987987-65-432109' },
                    { id: 7, name: 'Michael Wong', ic: '111222-33-445566' },
                    { id: 8, name: 'Linda Tan', ic: '999888-77-665544' },
                    { id: 9, name: 'James Smith', ic: '123456-78-901234' },
                    { id: 10, name: 'Emma Johnson', ic: '987654-32-109876' },
                ],
                partnerList: [
                    { id: 1, name: 'A. Rahman', firm_name: 'Rahman & Associates', email: 'arahman@rahmanassociates.com', contact: '+60 3-1234 5678', specialization: 'Civil Law' },
                    { id: 2, name: 'S. Kumar', firm_name: 'Kumar Legal Services', email: 'skumar@kumarlegal.com', contact: '+60 3-2345 6789', specialization: 'Criminal Law' },
                    { id: 3, name: 'M. Lim', firm_name: 'Lim & Partners', email: 'mlim@limpartners.com', contact: '+60 3-3456 7890', specialization: 'Corporate Law' },
                    { id: 4, name: 'N. Tan', firm_name: 'Tan Legal Group', email: 'ntan@tanlegal.com', contact: '+60 3-4567 8901', specialization: 'Family Law' },
                    { id: 5, name: 'K. Wong', firm_name: 'Wong & Associates', email: 'kwong@wongassociates.com', contact: '+60 3-5678 9012', specialization: 'Property Law' },
                    { id: 6, name: 'R. Singh', firm_name: 'Singh Legal Services', email: 'rsingh@singhlegal.com', contact: '+60 3-6789 0123', specialization: 'Employment Law' },
                ],
                
                addPlaintiff() {
                    this.plaintiffs.push({ name: '', ic: '', phone: '', email: '' });
                },
                removePlaintiff(index) {
                    this.plaintiffs.splice(index, 1);
                },
                addDefendant() {
                    this.defendants.push({ name: '', ic: '', phone: '', email: '' });
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
                        file_id: '',
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
                            // Generate username from name + last 4 digits of IC
                            const name = client.name.toLowerCase().replace(/\s+/g, '');
                            const icLast4 = client.ic.slice(-4);
                            const username = name + icLast4 + '@client';
                            
                            // Generate 6-digit password with letters and numbers
                            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                            let password = '';
                            for (let i = 0; i < 6; i++) {
                                password += chars.charAt(Math.floor(Math.random() * chars.length));
                            }
                            
                            this.plaintiffs.push({ 
                                name: client.name, 
                                ic: client.ic, 
                                username: username, 
                                password: password 
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
                            // Generate username from name + last 4 digits of IC
                            const name = client.name.toLowerCase().replace(/\s+/g, '');
                            const icLast4 = client.ic.slice(-4);
                            const username = name + icLast4 + '@client';
                            
                            // Generate 6-digit password with letters and numbers
                            const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                            let password = '';
                            for (let i = 0; i < 6; i++) {
                                password += chars.charAt(Math.floor(Math.random() * chars.length));
                            }
                            
                            this.defendants.push({ 
                                name: client.name, 
                                ic: client.ic, 
                                username: username, 
                                password: password 
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
                }
            }">
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
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 2025-001" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">File Reference *</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., NF-00126" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Court Reference</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., CR-2025-001">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Type *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select case type...</option>
                                <option value="civil">Civil Case</option>
                                <option value="criminal">Criminal Case</option>
                                <option value="family">Family Law</option>
                                <option value="corporate">Corporate Law</option>
                                <option value="property">Property Law</option>
                                <option value="employment">Employment Law</option>
                                <option value="sharia">Sharia Law</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Judge Name</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Y.A. Dato' Ahmad">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Court Location</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select court location...</option>
                                
                                <!-- All -->
                                <optgroup label="All">
                                    <option value="Pejabat Tanah">Pejabat Tanah</option>
                                </optgroup>
                                
                                <!-- Kuala Lumpur & Putrajaya -->
                                <optgroup label="Kuala Lumpur & Putrajaya">
                                    <option value="Dewan Bandaraya Kuala Lumpur">Dewan Bandaraya Kuala Lumpur</option>
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
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select jurisdiction...</option>
                                <option value="mahkamah_persekutuan">Mahkamah Persekutuan</option>
                                <option value="mahkamah_rayuan">Mahkamah Rayuan</option>
                                <option value="mahkamah_tinggi">Mahkamah Tinggi</option>
                                <option value="mahkamah_sesyen">Mahkamah Sesyen</option>
                                <option value="mahkamah_majistret">Mahkamah Majistret</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Section</label>
                            <select id="section-select" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select section...</option>
                                <option value="civil">Civil</option>
                                <option value="criminal">Criminal</option>
                                <option value="conveyancing">Conveyancing</option>
                                <option value="probate">Probate / Letter of Administration</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Case Initiating Documents</label>
                            <select id="documents-select" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" disabled>
                                <option value="">Please select a section first...</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Total Claim (RM) *</label>
                            <input type="number" step="0.01" x-model="totalClaim" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required>
                        </div>
                    </div>
                </div>

                <!-- Client Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">people</span>
                        Client Information
                    </h3>
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-xs text-gray-600 ml-6">Add plaintiffs and defendants to this case</p>
                        <div class="flex gap-2">
                            <button type="button" @click="showPlaintiffDropdown = !showPlaintiffDropdown" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded-sm text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add</span>
                                Add Plaintiff
                            </button>
                            <button type="button" @click="showDefendantDropdown = !showDefendantDropdown" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-sm text-xs font-medium flex items-center">
                                <span class="material-icons text-xs mr-1">add</span>
                                Add Defendant
                            </button>
                        </div>
                    </div>
                    
                    <!-- Plaintiff Selection Dropdown -->
                    <div x-show="showPlaintiffDropdown" x-transition class="mb-4">
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <h4 class="text-xs font-medium text-gray-700 mb-3">Select Plaintiff</h4>
                            <select x-model="selectedPlaintiff" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Choose plaintiff from client list...</option>
                                <template x-for="client in clientList" :key="client.id">
                                    <option :value="client.id" x-text="client.name + ' - ' + client.ic"></option>
                                </template>
                            </select>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="showPlaintiffDropdown = false" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" @click="addSelectedPlaintiff()" class="px-3 py-1.5 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                    Add Plaintiff
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Defendant Selection Dropdown -->
                    <div x-show="showDefendantDropdown" x-transition class="mb-4">
                        <div class="bg-white p-4 rounded border border-gray-200">
                            <h4 class="text-xs font-medium text-gray-700 mb-3">Select Defendant</h4>
                            <select x-model="selectedDefendant" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Choose defendant from client list...</option>
                                <template x-for="client in clientList" :key="client.id">
                                    <option :value="client.id" x-text="client.name + ' - ' + client.ic"></option>
                                </template>
                            </select>
                            <div class="flex justify-end gap-2 mt-3">
                                <button type="button" @click="showDefendantDropdown = false" class="px-3 py-1.5 text-xs text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="button" @click="addSelectedDefendant()" class="px-3 py-1.5 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                    Add Defendant
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Plaintiffs Information (within Client Information Section) -->
                    <div x-show="plaintiffs.length > 0" x-transition class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <span class="material-icons text-green-600 text-base mr-2">person</span>
                            Plaintiffs Information
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(plaintiff, index) in plaintiffs" :key="index">
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Plaintiff <span x-text="index + 1"></span></span>
                                        <button type="button" @click="removePlaintiff(index)" class="text-red-600 hover:text-red-800 text-xs">
                                            <span class="material-icons text-xs">remove_circle</span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Name *</label>
                                            <input type="text" x-model="plaintiff.name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Full name" required disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">IC/Passport</label>
                                            <input type="text" x-model="plaintiff.ic" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="IC or passport number" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Username</label>
                                            <input type="text" x-model="plaintiff.username" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username" required disabled>
                                        </div>
                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                            <input type="password" x-model="plaintiff.password" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" required disabled>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Defendants Information (within Client Information Section) -->
                    <div x-show="defendants.length > 0" x-transition class="mt-6 pb-6">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <span class="material-icons text-red-600 text-base mr-2">person</span>
                            Defendants Information
                        </h4>
                        <div class="space-y-3">
                            <template x-for="(defendant, index) in defendants" :key="index">
                                <div class="bg-white p-3 rounded border border-gray-200">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-medium text-gray-600">Defendant <span x-text="index + 1"></span></span>
                                        <button type="button" @click="removeDefendant(index)" class="text-red-600 hover:text-red-800 text-xs">
                                            <span class="material-icons text-xs">remove_circle</span>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Name *</label>
                                            <input type="text" x-model="defendant.name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Full name" required disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">IC/Passport</label>
                                            <input type="text" x-model="defendant.ic" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="IC or passport number" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Username</label>
                                            <input type="text" x-model="defendant.username" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username" required disabled>
                        </div>
                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Password</label>
                                            <input type="password" x-model="defendant.password" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Password" required disabled>
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
                                            <input type="text" x-model="partner.firm_name" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Firm Name" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" x-model="partner.email" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., info@firmname.com" disabled>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                                            <input type="text" x-model="partner.contact" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 1234 567890" disabled>
                        </div>
                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Specialization</label>
                                            <input type="text" x-model="partner.specialization" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Civil Law" disabled>
                                        </div>
                                    </div>
                                </div>
                            </template>
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
                    
                    <!-- Upload Button -->
                    <div class="mb-4">
                        <label class="block text-xs font-medium text-gray-700 mb-2">Upload Documents</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors cursor-pointer">
                            <span class="material-icons text-gray-400 text-2xl mb-2">cloud_upload</span>
                            <p class="text-xs text-gray-600">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX, JPG, PNG (Max 10MB)</p>
                            <input type="file" multiple class="hidden" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <!-- Documents Table -->
                    <div class="bg-white rounded border border-gray-200 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-700">No. File ID</th>
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
                                            <td class="px-3 py-2">
                                                <input type="text" x-model="document.file_id" class="w-full px-2 py-1 border border-gray-300 rounded text-xs" placeholder="File ID">
                                            </td>
                                            <td class="px-3 py-2">
                                                <select x-model="document.type" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                                    <option value="">Select type...</option>
                                                    <option value="writ">Writ of Summons</option>
                                                    <option value="statement">Statement of Claim</option>
                                                    <option value="defense">Statement of Defense</option>
                                                    <option value="affidavit">Affidavit</option>
                                                    <option value="evidence">Evidence</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="text" x-model="document.filed_by" class="w-full px-2 py-1 border border-gray-300 rounded text-xs" placeholder="Filed by">
                                            </td>
                                            <td class="px-3 py-2">
                                                <input type="date" x-model="document.filing_date" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                            </td>
                                            <td class="px-3 py-2">
                                                <select x-model="document.status" class="w-full px-2 py-1 border border-gray-300 rounded text-xs">
                                                    <option value="pending">Pending</option>
                                                    <option value="filed">Filed</option>
                                                    <option value="approved">Approved</option>
                                                    <option value="rejected">Rejected</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 text-center">
                                                <button type="button" @click="removeDocument(index)" class="text-red-600 hover:text-red-800 text-xs">
                                                    <span class="material-icons text-base">delete</span>
                                                </button>
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
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter financial details and payment terms</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Total Claim (RM) *</label>
                            <input type="number" step="0.01" x-model="totalClaim" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Retainer Amount (RM)</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Payment Terms</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select payment terms...</option>
                                <option value="upfront">Upfront Payment</option>
                                <option value="installment">Installment</option>
                                <option value="monthly">Monthly</option>
                                <option value="upon_completion">Upon Completion</option>
                                <option value="hourly">Hourly Rate</option>
                            </select>
                        </div>
                    </div>
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
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Status *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select status...</option>
                                <option value="consultation">Consultation</option>
                                <option value="quotation">Quotation</option>
                                <option value="open_file" selected>Open File</option>
                                <option value="proceed">Proceed</option>
                                <option value="closed_file">Closed File</option>
                                <option value="cancel">Cancel</option>
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
                        Create Case
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section-select');
        const documentsSelect = document.getElementById('documents-select');
        
        const documentOptions = {
            civil: [
                { value: 'originating_summons', text: 'Originating Summons' },
                { value: 'judgment_debtor_summons', text: 'Judgment Debtor Summons' },
                { value: 'writ_of_execution', text: 'Writ of Execution' },
                { value: 'writ_of_summons', text: 'Writ of Summons' }
            ],
            criminal: [
                { value: 'charge_sheet_alternative', text: 'Charge Sheet / Alternative Charge' },
                { value: 'criminal_application_motion', text: 'Criminal Application / Motion' },
                { value: 'summons_charge_sheet', text: 'Summons and Charge Sheet / Alternative Charge' }
            ],
            conveyancing: [
                { value: 'letter_offer', text: 'Letter Offer' },
                { value: 'sale_purchase_agreement', text: 'Sale & Purchase Agreement' },
                { value: 'loan_agreement', text: 'Loan Agreement/ Facility Agreement' }
            ],
            probate: [
                { value: 'originating_summons_exparte', text: 'Originating Summons (Ex-Parte)' },
                { value: 'form_p', text: 'Form P' }
            ]
        };
        
        sectionSelect.addEventListener('change', function() {
            const selectedSection = this.value;
            documentsSelect.innerHTML = '<option value="">Select document...</option>';
            
            if (selectedSection && documentOptions[selectedSection]) {
                documentsSelect.disabled = false;
                documentOptions[selectedSection].forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.text;
                    documentsSelect.appendChild(optionElement);
                });
            } else {
                documentsSelect.disabled = true;
                documentsSelect.innerHTML = '<option value="">Please select a section first...</option>';
            }
        });
    });
</script> 