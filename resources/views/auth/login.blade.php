<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100" x-data="{ showDisclaimer:false, showPrivacy:false, showTerms:false }">
        <div class="bg-white p-6 rounded-base shadow-lg w-96 text-center">
            <!-- Logo -->
            <div class="mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="Naeelah Firm Logo" class="max-w-[40%] h-auto mx-auto">
                <h1 class="text-sm font-bold text-gray-600 mt-1">NAAELAH SALEH & CO</h1>
            </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
                <div class="relative mb-3">
                    <i class="fas fa-user absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus 
                        autocomplete="username"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Email"
                    />
        </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />

        <!-- Password -->
                <div class="relative mb-3">
                    <i class="fas fa-lock absolute left-3 top-2.5 text-gray-400 text-xs"></i>
                    <input 
                        id="password" 
                            type="password"
                            name="password"
                        required 
                        autocomplete="current-password"
                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="**********"
                    />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />

                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full bg-green-500 hover:bg-green-600 text-white py-2 rounded text-xs font-medium transition-colors"
                >
                    Access
                </button>
            </form>
        </div>

        <!-- Disclaimer / Privacy / Terms -->
        <div class="text-center mt-2 text-xs text-gray-500">
            <button type="button" @click="showDisclaimer=true" class="hover:underline underline-offset-2 hover:text-blue-600 transition-colors">Disclaimer</button>
            <span class="mx-1">/</span>
            <button type="button" @click="showPrivacy=true" class="hover:underline underline-offset-2 hover:text-blue-600 transition-colors">Privacy Policy</button>
            <span class="mx-1">/</span>
            <button type="button" @click="showTerms=true" class="hover:underline underline-offset-2 hover:text-blue-600 transition-colors">Terms of Use</button>
        </div>

        <!-- Modals -->
        <template x-if="showDisclaimer">
            <div class="fixed inset-0 z-50" aria-modal="true" role="dialog">
                <div class="absolute inset-0 bg-black/40" @click="showDisclaimer=false"></div>
                <div class="relative mx-auto mt-16 w-full max-w-2xl bg-white rounded-md shadow-lg p-6">
                    <div class="flex justify-between items-start">
                        <h3 class="text-sm font-semibold text-gray-800">Disclaimer</h3>
                        <button class="text-gray-500 hover:text-gray-700" @click="showDisclaimer=false"><span class="material-icons text-base">close</span></button>
                    </div>
                    <div class="mt-3 text-xs text-gray-700 leading-relaxed space-y-3 max-h-[70vh] overflow-y-auto">
                        <h4 class="text-xs font-semibold">Legal Disclaimer for Law Firm Management System</h4>
                        <p><strong>Naaelah Saleh &amp; Co.</strong><br>(Advocates &amp; Solicitors)<br>METRO PARK, METRO SENDAYAN, 59-1,<br>Sendayan, Bandar Sri Sendayan,<br>71950 Seremban, Negeri Sembilan<br>Phone: 013-851 1400 | Email: <a href="mailto:enquiry@naaelahsaleh.my" class="text-blue-600 hover:underline">enquiry@naaelahsaleh.my</a><br>Website: <a href="https://www.naaelahsaleh.my" target="_blank" class="text-blue-600 hover:underline">https://www.naaelahsaleh.my</a></p>
                        <p class="text-gray-500">Effective Date: 12 August 2025</p>

                        <h4 class="text-xs font-semibold">General Disclaimer</h4>
                        <p>The Law Firm Management System ("the System") provided by Naaelah Saleh &amp; Co. ("the Firm") is an internal administrative tool designed to assist in case management, client registration, partner and user management, digital filing, billing, and other administrative functions. This disclaimer outlines important limitations and disclaimers regarding the use of this System.</p>

                        <h4 class="text-xs font-semibold">No Legal Advice</h4>
                        <p>The System is intended solely as an administrative and case management tool for authorized personnel of Naaelah Saleh &amp; Co. It does not constitute legal advice, and users should not rely on the System for legal guidance, case strategy, or professional legal judgment. All legal decisions, case strategies, and professional judgments must be made by qualified legal professionals based on their independent analysis and expertise.</p>

                        <h4 class="text-xs font-semibold">Accuracy and Reliability</h4>
                        <p>The System is provided on an "as-is" and "as-available" basis. While the Firm strives to maintain accurate and up-to-date information within the System, we make no representations or warranties regarding the accuracy, completeness, reliability, or timeliness of any information, data, or content contained within the System. Users are responsible for independently verifying all critical information, including court filing deadlines, statutes of limitations, client instructions, and other time-sensitive matters.</p>

                        <h4 class="text-xs font-semibold">System Availability</h4>
                        <p>The System may be temporarily unavailable due to scheduled maintenance, emergency repairs, technical issues, power outages, network failures, or other circumstances beyond the Firm's control. The Firm does not guarantee uninterrupted access to the System and shall not be liable for any damages, missed deadlines, loss of information, or other consequences resulting from system downtime or unavailability.</p>

                        <h4 class="text-xs font-semibold">Security and Confidentiality</h4>
                        <p>The System contains highly sensitive and confidential information, including client data, case strategy, and work product protected by solicitor-client privilege. While the Firm implements reasonable security measures in accordance with the Malaysian Personal Data Protection Act (PDPA) 2010, no system is completely secure. The Firm cannot guarantee that the System will be free from all security breaches, viruses, or other malicious components. Users must exercise appropriate caution and follow all Firm security protocols.</p>

                        <h4 class="text-xs font-semibold">Limitation of Liability</h4>
                        <p>To the fullest extent permitted by Malaysian law, Naaelah Saleh &amp; Co., its partners, employees, and agents shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising from the use of or inability to use the System, including but not limited to damages for loss of profits, revenue, data, or goodwill.</p>

                        <h4 class="text-xs font-semibold">User Responsibility</h4>
                        <p>Users are solely responsible for maintaining the confidentiality of their login credentials and for all activities conducted under their accounts. Users must report any suspected security breaches or unauthorized access immediately. The Firm reserves the right to monitor system usage and take appropriate action against any misuse or unauthorized access.</p>

                        <h4 class="text-xs font-semibold">Acceptance</h4>
                        <p>By accessing and using the System, users acknowledge that they have read, understood, and agree to be bound by this disclaimer. If you do not agree with any part of this disclaimer, you must not use the System. Continued use of the System constitutes acceptance of this disclaimer and any modifications thereto.</p>

                        <h4 class="text-xs font-semibold">Contact Information</h4>
                        <p>For questions regarding this disclaimer or to report security concerns, please contact the Firm's System Administrator at <a href="mailto:enquiry@naaelahsaleh.my" class="text-blue-600 hover:underline">enquiry@naaelahsaleh.my</a>.</p>
                    </div>
                    <div class="mt-4 text-right">
                        <button class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs" @click="showDisclaimer=false">Close</button>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="showPrivacy">
            <div class="fixed inset-0 z-50" aria-modal="true" role="dialog">
                <div class="absolute inset-0 bg-black/40" @click="showPrivacy=false"></div>
                <div class="relative mx-auto mt-16 w-full max-w-2xl bg-white rounded-md shadow-lg p-6">
                    <div class="flex justify-between items-start">
                        <h3 class="text-sm font-semibold text-gray-800">Privacy Policy</h3>
                        <button class="text-gray-500 hover:text-gray-700" @click="showPrivacy=false"><span class="material-icons text-base">close</span></button>
                    </div>
                    <div class="mt-3 text-xs text-gray-700 leading-relaxed space-y-3 max-h-[70vh] overflow-y-auto">
                        <h4 class="text-xs font-semibold">Privacy Policy for Law Firm Management System</h4>
                        <p><strong>Naaelah Saleh &amp; Co.</strong><br>(Advocates &amp; Solicitors)<br>METRO PARK, METRO SENDAYAN, 59-1,<br>Sendayan, Bandar Sri Sendayan,<br>71950 Seremban, Negeri Sembilan<br>Phone: 013-851 1400 | Email: <a href="mailto:enquiry@naaelahsaleh.my" class="text-blue-600 hover:underline">enquiry@naaelahsaleh.my</a><br>Website: <a href="https://www.naaelahsaleh.my" target="_blank" class="text-blue-600 hover:underline">https://www.naaelahsaleh.my</a></p>
                        <p class="text-gray-500">Effective Date: 12 August 2025</p>

                        <h4 class="text-xs font-semibold">1. Introduction</h4>
                        <p>Naaelah Saleh &amp; Co. ("the Firm," "we," "our," or "us") is committed to protecting the privacy and confidentiality of personal data collected, processed, and stored through our Law Firm Management System ("the System"). This Privacy Policy explains how we collect, use, disclose, and safeguard personal information in accordance with the Malaysian Personal Data Protection Act (PDPA) 2010 and other applicable laws.</p>

                        <h4 class="text-xs font-semibold">2. Information We Collect</h4>
                        <p><strong>Personal Data:</strong> We collect personal information including names, contact details, identification numbers, addresses, employment information, financial data, and other information necessary for legal case management and client services.</p>
                        <p><strong>System Usage Data:</strong> We collect information about how users interact with the System, including login times, access patterns, and system activities for security and operational purposes.</p>
                        <p><strong>Legal Case Information:</strong> We process sensitive information related to legal cases, including case details, court documents, client communications, and legal strategies, all protected by solicitor-client privilege.</p>

                        <h4 class="text-xs font-semibold">3. How We Use Your Information</h4>
                        <p>We use the collected information for the following purposes:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Providing legal services and case management</li>
                            <li>Client relationship management and communication</li>
                            <li>Billing and financial administration</li>
                            <li>Compliance with legal and regulatory requirements</li>
                            <li>System security and access control</li>
                            <li>Quality assurance and professional development</li>
                            <li>Internal administrative and operational purposes</li>
                        </ul>

                        <h4 class="text-xs font-semibold">4. Legal Basis for Processing</h4>
                        <p>We process personal data based on the following legal grounds:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Contract Performance:</strong> Processing necessary for the provision of legal services</li>
                            <li><strong>Legal Obligation:</strong> Compliance with applicable laws and regulations</li>
                            <li><strong>Legitimate Interest:</strong> Business operations and client relationship management</li>
                            <li><strong>Consent:</strong> Where explicitly provided by data subjects</li>
                        </ul>

                        <h4 class="text-xs font-semibold">5. Data Sharing and Disclosure</h4>
                        <p>We may share personal data with the following parties:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Authorized Personnel:</strong> Firm partners, associates, and staff with legitimate business need</li>
                            <li><strong>Service Providers:</strong> IT vendors, cloud service providers, and other third-party contractors under strict confidentiality agreements</li>
                            <li><strong>Legal Authorities:</strong> Courts, regulatory bodies, or law enforcement when required by law</li>
                            <li><strong>Other Parties:</strong> Only with explicit consent or as necessary for legal proceedings</li>
                        </ul>

                        <h4 class="text-xs font-semibold">6. Data Security Measures</h4>
                        <p>We implement comprehensive security measures to protect personal data:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Encryption of data in transit and at rest</li>
                            <li>Access controls and authentication mechanisms</li>
                            <li>Regular security audits and vulnerability assessments</li>
                            <li>Employee training on data protection and confidentiality</li>
                            <li>Incident response and breach notification procedures</li>
                            <li>Physical and environmental security controls</li>
                        </ul>

                        <h4 class="text-xs font-semibold">7. Data Retention</h4>
                        <p>We retain personal data for as long as necessary to fulfill the purposes outlined in this policy, comply with legal obligations, resolve disputes, and enforce agreements. Legal case files and related information may be retained for extended periods as required by professional standards and legal requirements.</p>

                        <h4 class="text-xs font-semibold">8. Your Rights Under PDPA</h4>
                        <p>Under the PDPA, you have the following rights:</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><strong>Access:</strong> Request access to your personal data</li>
                            <li><strong>Correction:</strong> Request correction of inaccurate or incomplete data</li>
                            <li><strong>Withdrawal of Consent:</strong> Withdraw consent for processing (where applicable)</li>
                            <li><strong>Complaint:</strong> Lodge complaints with the Personal Data Protection Commissioner</li>
                        </ul>

                        <h4 class="text-xs font-semibold">9. International Data Transfers</h4>
                        <p>Personal data may be transferred to and processed in countries outside Malaysia. We ensure that such transfers comply with applicable data protection laws and implement appropriate safeguards to protect your information.</p>

                        <h4 class="text-xs font-semibold">10. Cookies and Tracking Technologies</h4>
                        <p>The System may use cookies and similar technologies to enhance functionality, security, and user experience. These technologies help us maintain session information, prevent unauthorized access, and improve system performance.</p>

                        <h4 class="text-xs font-semibold">11. Changes to This Policy</h4>
                        <p>We may update this Privacy Policy from time to time to reflect changes in our practices, legal requirements, or system functionality. We will notify users of significant changes through appropriate channels, and continued use of the System constitutes acceptance of the updated policy.</p>

                        <h4 class="text-xs font-semibold">12. Contact Us</h4>
                        <p>If you have questions about this Privacy Policy or wish to exercise your rights under the PDPA, please contact our Data Protection Officer at <a href="mailto:enquiry@naaelahsaleh.my" class="text-blue-600 hover:underline">enquiry@naaelahsaleh.my</a> or write to us at the address above.</p>
                    </div>
                    <div class="mt-4 text-right">
                        <button class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs" @click="showPrivacy=false">Close</button>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="showTerms">
            <div class="fixed inset-0 z-50" aria-modal="true" role="dialog">
                <div class="absolute inset-0 bg-black/40" @click="showTerms=false"></div>
                <div class="relative mx-auto mt-16 w-full max-w-2xl bg-white rounded-md shadow-lg p-6">
                    <div class="flex justify-between items-start">
                        <h3 class="text-sm font-semibold text-gray-800">Terms of Use</h3>
                        <button class="text-gray-500 hover:text-gray-700" @click="showTerms=false"><span class="material-icons text-base">close</span></button>
                    </div>
                    <div class="mt-3 text-xs text-gray-700 leading-relaxed space-y-3 max-h-[70vh] overflow-y-auto">
                        <h4 class="text-xs font-semibold">Terms of Use for Law Firm Management System</h4>
                        <p><strong>Naaelah Saleh &amp; Co.</strong><br>(Advocates &amp; Solicitors)<br>METRO PARK, METRO SENDAYAN, 59-1,<br>Sendayan, Bandar Sri Sendayan,<br>71950 Seremban, Negeri Sembilan<br>Phone: 013-851 1400 | Email: <a href="mailto:enquiry@naaelahsaleh.my" class="text-blue-600 hover:underline">enquiry@naaelahsaleh.my</a><br>Website: <a href="https://www.naaelahsaleh.my" target="_blank" class="text-blue-600 hover:underline">https://www.naaelahsaleh.my</a></p>
                        <p class="text-gray-500">Effective Date: 12 August 2025</p>

                        <h4 class="text-xs font-semibold">1. Introduction and Acceptance</h4>
                        <p>These Terms of Use ("Terms") govern your access to and use of the proprietary Law Firm Management System ("the System") provided by Naaelah Saleh &amp; Co. ("the Firm," "we," "our," or "us"). The System is an internal administrative tool designed to assist in case management, client registration, partner and user management, digital filing, billing, and other administrative functions. By accessing, logging into, or otherwise using the System, you ("the User," "you," or "your") acknowledge that you have read, understood, and agree to be bound by these Terms. If you do not agree with these Terms, you are prohibited from using the System.</p>

                        <h4 class="text-xs font-semibold">2. System Purpose and Scope</h4>
                        <p>The System is intended for authorized professional use by partners, associates, paralegals, and administrative staff of Naaelah Saleh &amp; Co. Its primary purposes include streamlining firm operations, maintaining organized client and case files, managing billing and financial records, and facilitating internal communication and collaboration. The System serves as a tool to support professional judgment and administrative efficiency, not to replace professional legal expertise or decision-making.</p>

                        <h4 class="text-xs font-semibold">3. User Eligibility and Authorization</h4>
                        <p>Access to the System is restricted to authorized personnel of Naaelah Saleh &amp; Co. who have been granted specific permissions by the Firm's management. Users must be current employees, partners, or authorized contractors of the Firm. The Firm reserves the right to grant, modify, or revoke access privileges at any time based on employment status, job responsibilities, or security considerations. Unauthorized access attempts may result in legal action and reporting to relevant authorities.</p>

                        <h4 class="text-xs font-semibold">4. User Account Responsibilities</h4>
                        <p><strong>Account Security:</strong> Users are solely responsible for maintaining the confidentiality and security of their login credentials, including usernames, passwords, and any additional authentication factors. Users must not share their credentials with others or allow unauthorized individuals to access the System under their accounts. Any activity occurring under a user's account will be deemed to have been performed by that user.</p>
                        <p><strong>Immediate Reporting:</strong> Users must immediately report any suspected security breaches, unauthorized access, or suspicious activities to the Firm's System Administrator or IT Department. Failure to report such incidents promptly may result in disciplinary action and potential legal consequences.</p>
                        <p><strong>Account Management:</strong> Users must keep their account information current and accurate. Changes to employment status, job responsibilities, or contact information must be reported to the System Administrator promptly.</p>

                        <h4 class="text-xs font-semibold">5. Acceptable Use Policy</h4>
                        <p><strong>Authorized Use:</strong> The System may only be used for official Firm business purposes. This includes case management, client communication, document preparation, billing, and other legitimate professional activities. Personal use, commercial activities unrelated to the Firm, or any unauthorized purposes are strictly prohibited.</p>
                        <p><strong>Prohibited Activities:</strong> Users must not engage in any activities that could compromise system security, including but not limited to: attempting to gain unauthorized access to other accounts or system areas; introducing viruses, malware, or other harmful code; interfering with system operations or other users' access; or using the System for illegal activities.</p>
                        <p><strong>Data Integrity:</strong> Users are responsible for the accuracy and completeness of all data they enter into the System. Deliberate entry of false, misleading, or incomplete information is strictly prohibited and may result in immediate account termination and legal action.</p>

                        <h4 class="text-xs font-semibold">6. Data Protection and Confidentiality</h4>
                        <p><strong>Solicitor-Client Privilege:</strong> The System contains highly sensitive, confidential, and privileged information protected by solicitor-client privilege. Users must treat all information within the System as confidential and privileged, regardless of their role or access level. Breach of confidentiality may result in immediate termination and potential legal action.</p>
                        <p><strong>Data Handling:</strong> Users must follow all Firm policies regarding data handling, storage, and transmission. Information from the System must not be downloaded, copied, transmitted, or stored on unauthorized devices, cloud services, or external systems without explicit permission from the Firm's management.</p>
                        <p><strong>Third-Party Sharing:</strong> Users must not share, disclose, or transmit System information to third parties without proper authorization and appropriate confidentiality agreements in place.</p>

                        <h4 class="text-xs font-semibold">7. System Availability and Maintenance</h4>
                        <p><strong>Service Availability:</strong> The System is provided on an "as-is" and "as-available" basis. While we strive to maintain high availability, the System may be temporarily unavailable due to scheduled maintenance, emergency repairs, technical issues, or circumstances beyond our control. We do not guarantee uninterrupted access or specific uptime percentages.</p>
                        <p><strong>Maintenance Notices:</strong> We will provide reasonable notice of scheduled maintenance that may affect system availability. Emergency maintenance may be performed without prior notice to address critical security or performance issues.</p>
                        <p><strong>Backup and Recovery:</strong> We implement regular backup procedures to protect against data loss. However, users should not rely solely on system backups and should maintain their own records of critical information and work product.</p>

                        <h4 class="text-xs font-semibold">8. Intellectual Property Rights</h4>
                        <p><strong>System Ownership:</strong> The System, including its software, design, source code, user interface, documentation, and all content generated by the Firm (excluding client-specific data), is the exclusive intellectual property of Naaelah Saleh &amp; Co. and/or its licensors. All rights, title, and interest in the System remain with the Firm.</p>
                        <p><strong>Prohibited Activities:</strong> Users are strictly prohibited from copying, modifying, distributing, selling, reverse-engineering, decompiling, disassembling, or creating derivative works of the System or any of its components. Any attempt to circumvent system security measures or access source code is strictly forbidden.</p>
                        <p><strong>User-Generated Content:</strong> While users may create content within the System, the Firm retains ownership of the platform and may use aggregated, anonymized data for system improvement and business analytics purposes.</p>

                        <h4 class="text-xs font-semibold">9. Limitation of Liability and Disclaimers</h4>
                        <p><strong>No Warranties:</strong> The Firm makes no warranties, express or implied, regarding the System's reliability, accuracy, completeness, or fitness for a particular purpose. The System is provided without warranty of any kind, including but not limited to warranties of merchantability, non-infringement, or fitness for a particular purpose.</p>
                        <p><strong>Limitation of Liability:</strong> To the fullest extent permitted by Malaysian law, Naaelah Saleh &amp; Co., its partners, employees, and agents shall not be liable for any direct, indirect, incidental, special, consequential, or punitive damages arising from the use of or inability to use the System, including but not limited to damages for loss of profits, revenue, data, or goodwill.</p>
                        <p><strong>Force Majeure:</strong> The Firm shall not be liable for any failure or delay in performance due to circumstances beyond its reasonable control, including but not limited to natural disasters, government actions, labor disputes, or technical failures.</p>

                        <h4 class="text-xs font-semibold">10. Termination and Suspension</h4>
                        <p><strong>Account Termination:</strong> The Firm may terminate or suspend user accounts at any time for violations of these Terms, security concerns, or changes in employment status. Upon termination, users must immediately cease all use of the System and return any Firm property or confidential information in their possession.</p>
                        <p><strong>Data Retention:</strong> Following account termination, the Firm may retain user data for legal, regulatory, or business purposes as required by applicable laws and professional standards.</p>
                        <p><strong>Survival of Terms:</strong> Certain provisions of these Terms, including those relating to confidentiality, intellectual property, and limitation of liability, shall survive termination of user accounts.</p>

                        <h4 class="text-xs font-semibold">11. Modification of Terms</h4>
                        <p>The Firm reserves the right to modify these Terms at any time to reflect changes in system functionality, legal requirements, or business practices. We will provide notice of significant changes through appropriate channels, such as system notifications, email communications, or posted notices. Continued use of the System after any modification constitutes acceptance of the updated Terms.</p>

                        <h4 class="text-xs font-semibold">12. Governing Law and Dispute Resolution</h4>
                        <p><strong>Governing Law:</strong> These Terms shall be governed by and construed in accordance with the laws of Malaysia, without regard to conflict of law principles.</p>
                        <p><strong>Jurisdiction:</strong> Any disputes arising out of or in connection with these Terms shall be subject to the exclusive jurisdiction of the courts of Malaysia. Users agree to submit to the personal jurisdiction of such courts.</p>
                        <p><strong>Dispute Resolution:</strong> Before pursuing formal legal action, parties agree to attempt to resolve disputes through good faith negotiation and, if necessary, mediation in accordance with Malaysian law.</p>

                        <h4 class="text-xs font-semibold">13. Severability and Waiver</h4>
                        <p>If any provision of these Terms is found to be invalid, illegal, or unenforceable, the remaining provisions shall continue in full force and effect. The failure of the Firm to enforce any right or provision of these Terms shall not constitute a waiver of such right or provision.</p>

                        <h4 class="text-xs font-semibold">14. Contact Information</h4>
                        <p>For questions regarding these Terms of Use or to report violations, please contact the Firm's System Administrator or IT Department at <a href="mailto:enquiry@naaelahsaleh.my" class="text-blue-600 hover:underline">enquiry@naaelahsaleh.my</a> or write to us at the address above.</p>
                    </div>
                    <div class="mt-4 text-right">
                        <button class="px-3 py-1.5 bg-blue-600 text-white rounded text-xs" @click="showTerms=false">Close</button>
                    </div>
                </div>
            </div>
        </template>
        </div>
</x-guest-layout>
