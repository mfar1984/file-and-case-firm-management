@extends('layouts.app')

@section('breadcrumb')
    Client > Add New Client
@endsection

@section('content')
<div class="px-4 md:px-6 pt-4 md:pt-6 pb-6 max-w-7xl mx-auto">
    <div class="bg-white rounded shadow-md border border-gray-300">
        <div class="p-4 md:p-6 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center">
                        <span class="material-icons mr-2 text-blue-600">person_add</span>
                        <h1 class="text-lg md:text-xl font-bold text-gray-800">Add New Client</h1>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Create a new client profile with all necessary information.</p>
                </div>
            </div>
        </div>
        
        <div class="p-4 md:p-6">
            <form action="{{ route('client.store') }}" method="POST" class="space-y-0" x-data="{
                partyType: '{{ old('party_type') }}',
                identityType: '{{ old('identity_type') }}',
                identityNumber: '{{ old('identity_number') }}',
                name: '{{ old('name') }}',
                gender: '{{ old('gender') }}',
                nationality: '{{ old('nationality', 'Malaysia') }}',
                race: '{{ old('race') }}',
                state: '{{ old('state') }}',
                country: '{{ old('country', 'Malaysia') }}',
                
                identityTypes: [
                    'Government Agency (Criminal)',
                    'Government Agency (Civil)',
                    'Identity Card (New)',
                    'Identity Card (Old)',
                    'Passport No.',
                    'Business Registration No.',
                    'Limited Liability Partnership No.',
                    'Police No.',
                    'Company No.',
                    'Military No.',
                    'Others'
                ],
                
                races: [
                    'Melayu', 'Cina', 'India', 'Sikh', 'Peranakan Baba-Nyonya', 'Chitty', 
                    'Keturunan Bugis', 'Orang Asli', 'Dayak', 'Iban', 'Bidayuh', 'Orang Ulu', 
                    'Kadazan Dusun', 'Bajau', 'Murut', 'Melanau', 'Lain-lain'
                ],
                
                states: [
                    'Wilayah Persekutuan Kuala Lumpur', 'Wilayah Persekutuan Labuan', 
                    'Wilayah Persekutuan Putrajaya', 'Johor', 'Kedah', 'Kelantan', 
                    'Melaka', 'Negeri Sembilan', 'Pahang', 'Pulau Pinang', 'Perak', 
                    'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu', 
                    'Singapura', 'Luar Negara'
                ],
                
                countries: [
                    'Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan',
                    'Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi',
                    'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo', 'Costa Rica', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic',
                    'Democratic Republic of the Congo', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic',
                    'East Timor', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Eswatini', 'Ethiopia',
                    'Fiji', 'Finland', 'France',
                    'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana',
                    'Haiti', 'Honduras', 'Hungary',
                    'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Ivory Coast',
                    'Jamaica', 'Japan', 'Jordan',
                    'Kazakhstan', 'Kenya', 'Kiribati', 'Kuwait', 'Kyrgyzstan',
                    'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg',
                    'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar',
                    'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'North Korea', 'North Macedonia', 'Norway',
                    'Oman',
                    'Pakistan', 'Palau', 'Palestine', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal',
                    'Qatar',
                    'Romania', 'Russia', 'Rwanda',
                    'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Korea', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Sweden', 'Switzerland', 'Syria',
                    'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu',
                    'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan',
                    'Vanuatu', 'Vatican City', 'Venezuela', 'Vietnam',
                    'Yemen',
                    'Zambia', 'Zimbabwe'
                ],
                activePersonalTab: 'identity',
                addresses: [{ address_line1:'{{ old('addresses.0.address_line1') }}', address_line2:'{{ old('addresses.0.address_line2') }}', address_line3:'{{ old('addresses.0.address_line3') }}', postcode:'{{ old('addresses.0.postcode') }}', city:'{{ old('addresses.0.city') }}', state:'{{ old('addresses.0.state') }}', country:'{{ old('addresses.0.country', 'Malaysia') }}' }],
                phone: '{{ old('phone') }}',
                fax: '{{ old('fax') }}',
                mobile: '{{ old('mobile') }}',
                email: '{{ old('email') }}'
            }">
                @csrf
                
                <!-- Party Type Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-blue-600 text-base mr-2">category</span>
                        Party Type *
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Select the type of party for this client</p>
                    
                    <!-- Tab Design -->
                    <div class="flex bg-white rounded-sm border border-gray-200 p-1 @error('party_type') border-red-500 @enderror">
                        <button 
                            type="button"
                            @click="partyType = 'applicant'"
                            :class="partyType === 'applicant' 
                                ? 'bg-blue-600 text-white shadow-sm' 
                                : 'bg-transparent text-gray-600 hover:bg-gray-50'"
                            class="flex-1 py-2 px-4 text-xs font-medium rounded-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        >
                            <span class="material-icons text-sm mr-2">person_add</span>
                            Applicant
                        </button>
                        <button 
                            type="button"
                            @click="partyType = 'respondent'"
                            :class="partyType === 'respondent' 
                                ? 'bg-blue-600 text-white shadow-sm' 
                                : 'bg-transparent text-gray-600 hover:bg-gray-50'"
                            class="flex-1 py-2 px-4 text-xs font-medium rounded-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                        >
                            <span class="material-icons text-sm mr-2">gavel</span>
                            Respondent
                        </button>
                    </div>
                    
                    <!-- Hidden input for form submission -->
                    <input type="hidden" name="party_type" x-model="partyType" required>
                    
                    @error('party_type')
                        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Personal Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">person</span>
                        Personal Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the client's personal details and identification</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Type of Identity *</label>
                            <select x-model="identityType" name="identity_type" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('identity_type') border-red-500 @enderror" required>
                                <option value="">Select identity type...</option>
                                <template x-for="type in identityTypes" :key="type">
                                    <option :value="type" x-text="type"></option>
                                </template>
                            </select>
                            @error('identity_type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2" x-text="identityType || 'Identity Number'">Identity Number *</label>
                            <input type="text" name="identity_number" x-model="identityNumber" value="{{ old('identity_number') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('identity_number') border-red-500 @enderror" placeholder="Enter identity number" required>
                            @error('identity_number')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Full Name *</label>
                            <input type="text" name="name" x-model="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" placeholder="Enter full name" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Gender *</label>
                            <div class="flex gap-4 items-center h-8 @error('gender') border border-red-500 rounded-sm p-2 @enderror">
                                <label class="flex items-center">
                                    <input type="radio" name="gender" value="male" x-model="gender" class="mr-2" required>
                                    <span class="text-xs text-gray-700">Male</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="gender" value="female" x-model="gender" class="mr-2">
                                    <span class="text-xs text-gray-700">Female</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="gender" value="not_specified" x-model="gender" class="mr-2">
                                    <span class="text-xs text-gray-700">Not Specified</span>
                                </label>
                            </div>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Nationality *</label>
                            <select x-model="nationality" name="nationality" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nationality') border-red-500 @enderror" required>
                                <template x-for="country in countries" :key="country">
                                    <option :value="country" x-text="country" :selected="country === 'Malaysia'"></option>
                                </template>
                            </select>
                            @error('nationality')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Race *</label>
                            <select x-model="race" name="race" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('race') border-red-500 @enderror" required>
                                <option value="">Select race...</option>
                                <template x-for="raceOption in races" :key="raceOption">
                                    <option :value="raceOption" x-text="raceOption"></option>
                                </template>
                            </select>
                            @error('race')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone" x-model="phone" value="{{ old('phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter phone number">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Fax Number</label>
                            <input type="tel" name="fax" x-model="fax" value="{{ old('fax') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter fax number">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Mobile Number</label>
                            <input type="tel" name="mobile" x-model="mobile" value="{{ old('mobile') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter mobile number">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" x-model="email" value="{{ old('email') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter email address">
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Address Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                <span class="material-icons text-blue-600 text-base mr-2">location_on</span>
                                Address
                            </h3>
                            <p class="text-xs text-gray-600 mb-4 ml-6">Add one or more addresses for this client</p>
                        </div>
                        <button type="button" @click="addresses.push({ address_line1:'', address_line2:'', address_line3:'', postcode:'', city:'', state:'', country:'Malaysia' })" class="h-7 px-2 rounded-sm bg-blue-600 text-white text-xs font-medium flex items-center">
                            <span class="material-icons text-xs mr-1">add</span>
                            Add Address
                        </button>
                    </div>

                    <template x-for="(addr, i) in addresses" :key="i">
                        <div class="mt-4 border border-gray-200 rounded-sm p-3 bg-white">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[11px] font-medium text-gray-700">Address <span x-text="i+1"></span></span>
                                <button type="button" @click="addresses.splice(i,1)" class="p-0.5 text-red-600 hover:text-red-700" title="Remove address">
                                    <span class="material-icons text-base">remove_circle</span>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Address Line 1</label>
                                    <input type="text" :name="`addresses[${i}][address_line1]`" x-model="addr.address_line1" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter address line 1">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Address Line 2</label>
                                    <input type="text" :name="`addresses[${i}][address_line2]`" x-model="addr.address_line2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter address line 2">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Address Line 3</label>
                                    <input type="text" :name="`addresses[${i}][address_line3]`" x-model="addr.address_line3" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter address line 3">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Postcode</label>
                                    <input type="text" :name="`addresses[${i}][postcode]`" x-model="addr.postcode" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter postcode">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">City</label>
                                    <input type="text" :name="`addresses[${i}][city]`" x-model="addr.city" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter city">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">State *</label>
                                    <select :name="`addresses[${i}][state]`" x-model="addr.state" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 @error('addresses.0.state') border-red-500 @enderror" required>
                                        <option value="">Select state...</option>
                                        <template x-for="stateOption in states" :key="stateOption">
                                            <option :value="stateOption" x-text="stateOption"></option>
                                        </template>
                                    </select>
                                    @error('addresses.0.state')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Country *</label>
                                    <select :name="`addresses[${i}][country]`" x-model="addr.country" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                        <template x-for="countryOption in countries" :key="countryOption">
                                            <option :value="countryOption" x-text="countryOption"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Financial Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-green-600 text-base mr-2">attach_money</span>
                        Financial Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the client's financial details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">TIN No</label>
                            <input type="text" name="tin_no" value="{{ old('tin_no') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tax Identification Number">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Job / Work</label>
                            <input type="text" name="job" value="{{ old('job') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Engineer, Business Owner">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Salary (RM)</label>
                            <input type="number" step="0.01" name="salary" value="{{ old('salary') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="0.00">
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Family Information Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-orange-600 text-base mr-2">family_restroom</span>
                        Family Information
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter the client's family contact details</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Dependent</label>
                            <input type="number" name="dependent" value="{{ old('dependent') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Number of dependents">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Family Contact Name</label>
                            <input type="text" name="family_contact_name" value="{{ old('family_contact_name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Siti binti Ahmad">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Family Contact Number</label>
                            <input type="tel" name="family_contact_phone" value="{{ old('family_contact_phone') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., +60 12-345 6789">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Family Address</label>
                            <textarea name="family_address" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Family contact address">{{ old('family_address') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- SPACER -->
                <div class="h-6 bg-transparent"></div>

                <!-- Professional Contacts Section -->
                <div class="bg-gray-50 p-4 rounded-sm mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1 flex items-center">
                        <span class="material-icons text-purple-600 text-base mr-2">business</span>
                        Professional Contacts
                    </h3>
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter professional contact information</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Agent / Banker</label>
                            <input type="text" name="agent_banker" value="{{ old('agent_banker') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Name of agent or banker">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Financier / Bank</label>
                            <input type="text" name="financier_bank" value="{{ old('financier_bank') }}" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Bank or financial institution">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-medium text-gray-700 mb-2">Lawyer(s) parties (if any)</label>
                            <textarea name="lawyers_parties" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Names of other lawyers involved in the case">{{ old('lawyers_parties') }}</textarea>
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
                    <p class="text-xs text-gray-600 mb-4 ml-6">Enter any additional notes or comments</p>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-2">Notes</label>
                            <textarea name="notes" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-sm text-xs focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Any additional notes or comments about the client...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col md:flex-row justify-end space-y-2 md:space-y-0 md:space-x-3 pt-4">
                    <a href="{{ route('client.index') }}" class="w-full md:w-auto px-3 py-1.5 text-gray-600 border border-gray-300 rounded-sm text-xs font-medium hover:bg-gray-50 text-center">
                        Cancel
                    </a>
                    <button 
                        type="submit" 
                        class="w-full md:w-auto px-3 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-medium hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="!partyType || !identityType || !identityNumber || !name || !gender || !nationality || !race || !addresses.length || !addresses[0].state || !addresses[0].country"
                    >
                        Create Client
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 