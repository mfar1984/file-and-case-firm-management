"use client";

import { useState } from "react";
import "./status-check.css";

interface CaseData {
  case_number: string;
  title: string;
  case_status: {
    name: string;
    status: string;
  };
  case_type: {
    name: string;
  };
  person_in_charge: string;
  court_ref: string;
  jurisdiction: string;
  priority_level: string;
  judge_name: string;
  court_location: string;
  claim_amount: string;
  created_at: string;
  parties: Array<{
    name: string;
    party_type: string;
    ic_passport: string;
  }>;
}

interface TimelineEvent {
  id: number;
  event_type: string;
  title: string;
  description: string;
  event_date: string;
  status: string;
  status_color: string;
  status_icon: string;
  status_label: string;
  custom_metadata: any;
  created_at: string;
}

export default function StatusCheck() {
  const [caseReference, setCaseReference] = useState("");
  const [icPassport, setIcPassport] = useState("");
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState("");
  const [caseData, setCaseData] = useState<CaseData | null>(null);
  const [timeline, setTimeline] = useState<TimelineEvent[]>([]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError("");
    setCaseData(null);
    setTimeline([]);

    try {
      // Call public API to get case status with IC/Passport verification
      const [statusResponse, timelineResponse] = await Promise.all([
        fetch(`https://apps.naaelahsaleh.co/api/public/case/${caseReference}/status`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            ic_passport: icPassport
          }),
        }),
        fetch(`https://apps.naaelahsaleh.co/api/public/case/${caseReference}/timeline`, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            ic_passport: icPassport
          }),
        })
      ]);

      const statusResult = await statusResponse.json();
      const timelineResult = await timelineResponse.json();

      if (statusResult.success && statusResult.data) {
        setCaseData(statusResult.data);

        // Set timeline if available
        if (timelineResult.success && timelineResult.data) {
          setTimeline(timelineResult.data);
        }
      } else {
        setError(statusResult.message || "Unable to retrieve case information. Please check your details.");
      }
    } catch (err) {
      console.error("Error fetching case data:", err);
      setError("Unable to connect to the server. Please try again later.");
    } finally {
      setLoading(false);
    }
  };

  const getStatusColor = (status: string) => {
    const statusColors: { [key: string]: string } = {
      'active': 'success',
      'pending': 'warning',
      'completed': 'info',
      'closed': 'secondary',
      'proceed': 'primary',
    };
    return statusColors[status.toLowerCase()] || 'secondary';
  };

  const getPriorityColor = (priority: string) => {
    const priorityColors: { [key: string]: string } = {
      'high': 'danger',
      'medium': 'warning',
      'low': 'info',
    };
    return priorityColors[priority.toLowerCase()] || 'secondary';
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-MY', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const formatCurrency = (amount: string) => {
    return new Intl.NumberFormat('en-MY', {
      style: 'currency',
      currency: 'MYR'
    }).format(parseFloat(amount));
  };

  const getEventTypeLabel = (eventType: string) => {
    const labels: { [key: string]: string } = {
      'case_filed': 'Case Filed',
      'case_created': 'Case Created',
      'hearing_scheduled': 'Hearing Scheduled',
      'court_hearing': 'Court Hearing',
      'document_filing': 'Document Filing',
      'judgment': 'Judgment',
      'settlement': 'Settlement',
      'appeal': 'Appeal',
      'case_review': 'Case Review',
      'evidence_submission': 'Evidence Submission',
      'pre_trial_conference': 'Pre-Trial Conference',
      'consultation': 'Consultation',
      'payment': 'Payment',
      'other': 'Other Event',
    };
    return labels[eventType] || eventType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
  };

  const getEventTypeIcon = (eventType: string) => {
    const icons: { [key: string]: string } = {
      'case_filed': 'bi-file-earmark-text',
      'case_created': 'bi-file-earmark-plus',
      'hearing_scheduled': 'bi-calendar-check',
      'court_hearing': 'bi-building',
      'document_filing': 'bi-file-earmark-arrow-up',
      'judgment': 'bi-gavel',
      'settlement': 'bi-handshake',
      'appeal': 'bi-arrow-repeat',
      'case_review': 'bi-search',
      'evidence_submission': 'bi-file-earmark-medical',
      'pre_trial_conference': 'bi-people',
      'consultation': 'bi-chat-dots',
      'payment': 'bi-credit-card',
      'other': 'bi-info-circle',
    };
    return icons[eventType] || 'bi-circle';
  };

  const getEventTypeColor = (eventType: string) => {
    const colors: { [key: string]: string } = {
      'case_filed': 'primary',
      'case_created': 'primary',
      'hearing_scheduled': 'info',
      'court_hearing': 'warning',
      'document_filing': 'secondary',
      'judgment': 'success',
      'settlement': 'success',
      'appeal': 'danger',
      'case_review': 'info',
      'evidence_submission': 'warning',
      'pre_trial_conference': 'info',
      'consultation': 'secondary',
      'payment': 'success',
      'other': 'secondary',
    };
    return colors[eventType] || 'secondary';
  };

  const getEventStatusColor = (statusColor: string) => {
    // Map Tailwind CSS classes from database to Bootstrap classes
    const colorMap: { [key: string]: string } = {
      'bg-green-500': 'success',    // completed
      'bg-blue-500': 'primary',     // active, in_progress, test_event
      'bg-yellow-500': 'warning',   // processing, pending
      'bg-red-500': 'danger',       // cancelled
      'bg-gray-500': 'secondary',   // default
    };
    return colorMap[statusColor] || 'secondary';
  };

  const getEventStatusLabel = (statusLabel: string) => {
    // Format the label (capitalize first letter, replace underscores)
    return statusLabel
      .replace(/_/g, ' ')
      .replace(/\b\w/g, l => l.toUpperCase());
  };

  return (
    <>
      <section id="status-hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Case Status Check</h1>
              <p>Track the progress of your legal matter securely</p>
            </div>
          </div>
        </div>
      </section>

      <section id="status-check" className="contact section">
        <div className="container" data-aos="fade-up">
          {!caseData ? (
            <div className="row justify-content-center">
              <div className="col-lg-8">
                <div className="status-check-card">
                  <div className="card-icon">
                    <i className="bi bi-search"></i>
                  </div>
                  <h3 className="mb-3">Check Your Case Status</h3>
                  <p className="text-center mb-4 text-muted">
                    Please provide your case reference number and IC/Passport number to securely access your case information.
                  </p>

                  <form onSubmit={handleSubmit} className="status-form">
                    <div className="form-group">
                      <label htmlFor="caseReference">
                        <i className="bi bi-folder2-open me-2"></i>
                        Case Reference Number
                      </label>
                      <input
                        type="text"
                        id="caseReference"
                        className="form-control"
                        placeholder="e.g., 2025-CA-1-MFBAR"
                        required
                        value={caseReference}
                        onChange={(e) => setCaseReference(e.target.value.toUpperCase())}
                      />
                      <small className="form-text">Format: YEAR-SECTION-NUMBER-CLIENT (e.g., 2025-CA-1-MFBAR)</small>
                    </div>

                    <div className="form-group">
                      <label htmlFor="icPassport">
                        <i className="bi bi-person-badge me-2"></i>
                        IC/Passport Number
                      </label>
                      <input
                        type="text"
                        id="icPassport"
                        className="form-control"
                        placeholder="e.g., 850101-01-1234 or A12345678"
                        required
                        value={icPassport}
                        onChange={(e) => setIcPassport(e.target.value)}
                      />
                      <small className="form-text">For verification purposes</small>
                    </div>

                    {error && (
                      <div className="alert alert-danger" role="alert">
                        <i className="bi bi-exclamation-triangle me-2"></i>
                        {error}
                      </div>
                    )}

                    <div className="text-center">
                      <button type="submit" className="btn-submit" disabled={loading}>
                        {loading ? (
                          <>
                            <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                            Checking...
                          </>
                        ) : (
                          <>
                            <i className="bi bi-search me-2"></i>
                            Check Status
                          </>
                        )}
                      </button>
                    </div>
                  </form>

                  <div className="security-note">
                    <i className="bi bi-shield-check me-2"></i>
                    Your information is secure and confidential
                  </div>
                </div>
              </div>
            </div>
          ) : (
            <div className="row">
              <div className="col-lg-12">
                <div className="case-result-header">
                  <button
                    className="btn btn-outline-secondary mb-3"
                    onClick={() => {
                      setCaseData(null);
                      setCaseReference("");
                      setIcPassport("");
                    }}
                  >
                    <i className="bi bi-arrow-left me-2"></i>
                    Check Another Case
                  </button>

                  <div className="result-title">
                    <i className="bi bi-check-circle-fill text-success me-2"></i>
                    Case Found
                  </div>
                </div>

                {/* Case Overview Card */}
                <div className="case-card case-overview">
                  <div className="case-card-header">
                    <h4>
                      <i className="bi bi-folder2-open me-2"></i>
                      Case Overview
                    </h4>
                    <span className={`badge bg-${getStatusColor(caseData.case_status.status)}`}>
                      {caseData.case_status.name}
                    </span>
                  </div>
                  <div className="case-card-body">
                    <div className="row">
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Case Reference</label>
                          <div className="info-value highlight">{caseData.case_number}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Case Title</label>
                          <div className="info-value">{caseData.title}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Case Type</label>
                          <div className="info-value">{caseData.case_type?.name || 'N/A'}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Priority Level</label>
                          <div className="info-value">
                            <span className={`badge bg-${getPriorityColor(caseData.priority_level)}`}>
                              {caseData.priority_level.toUpperCase()}
                            </span>
                          </div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Filed Date</label>
                          <div className="info-value">{formatDate(caseData.created_at)}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Person in Charge</label>
                          <div className="info-value">{caseData.person_in_charge}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                {/* Court Information Card */}
                <div className="case-card">
                  <div className="case-card-header">
                    <h4>
                      <i className="bi bi-building me-2"></i>
                      Court Information
                    </h4>
                  </div>
                  <div className="case-card-body">
                    <div className="row">
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Court Reference</label>
                          <div className="info-value">{caseData.court_ref}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Jurisdiction</label>
                          <div className="info-value">{caseData.jurisdiction.replace('_', ' ').toUpperCase()}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Judge Name</label>
                          <div className="info-value">{caseData.judge_name || 'Not Assigned'}</div>
                        </div>
                      </div>
                      <div className="col-md-6">
                        <div className="info-item">
                          <label>Court Location</label>
                          <div className="info-value">{caseData.court_location}</div>
                        </div>
                      </div>
                      {caseData.claim_amount && (
                        <div className="col-md-12">
                          <div className="info-item">
                            <label>Claim Amount</label>
                            <div className="info-value highlight">{formatCurrency(caseData.claim_amount)}</div>
                          </div>
                        </div>
                      )}
                    </div>
                  </div>
                </div>

                {/* Parties Information Card */}
                {caseData.parties && caseData.parties.length > 0 && (
                  <div className="case-card">
                    <div className="case-card-header">
                      <h4>
                        <i className="bi bi-people me-2"></i>
                        Parties Involved
                      </h4>
                    </div>
                    <div className="case-card-body">
                      <div className="parties-list">
                        {caseData.parties.map((party, index) => (
                          <div key={index} className="party-item">
                            <div className="party-type">
                              <span className={`badge ${party.party_type === 'plaintiff' ? 'bg-primary' : 'bg-secondary'}`}>
                                {party.party_type.toUpperCase()}
                              </span>
                            </div>
                            <div className="party-details">
                              <div className="party-name">{party.name}</div>
                              <div className="party-ic">
                                {party.ic_passport === icPassport && (
                                  <i className="bi bi-check-circle-fill text-success me-1"></i>
                                )}
                                IC/Passport: {party.ic_passport}
                              </div>
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>
                )}

                {/* Case Timeline Card */}
                {timeline && timeline.length > 0 && (
                  <div className="case-card timeline-card">
                    <div className="case-card-header">
                      <h4>
                        <i className="bi bi-clock-history me-2"></i>
                        Case Timeline
                      </h4>
                      <span className="badge bg-light text-dark">{timeline.length} Events</span>
                    </div>
                    <div className="case-card-body">
                      <div className="timeline-container">
                        {timeline.map((event, index) => (
                          <div key={event.id} className="timeline-item">
                            <div className="timeline-marker">
                              <div className={`timeline-icon bg-${getEventTypeColor(event.event_type)}`}>
                                <i className={`bi ${getEventTypeIcon(event.event_type)}`}></i>
                              </div>
                              {index < timeline.length - 1 && <div className="timeline-line"></div>}
                            </div>
                            <div className="timeline-content">
                              <div className="timeline-header">
                                <div className="timeline-title">
                                  <span className={`badge bg-${getEventTypeColor(event.event_type)} me-2`}>
                                    {getEventTypeLabel(event.event_type)}
                                  </span>
                                  <strong>{event.title}</strong>
                                  <span className={`badge bg-${getEventStatusColor(event.status_color)} ms-2`}>
                                    {getEventStatusLabel(event.status_label)}
                                  </span>
                                </div>
                                <div className="timeline-date">
                                  <i className="bi bi-calendar3 me-1"></i>
                                  {formatDate(event.event_date)}
                                </div>
                              </div>
                              {event.description && (
                                <div className="timeline-description">
                                  {event.description}
                                </div>
                              )}
                              {event.custom_metadata && (
                                <div className="timeline-metadata">
                                  <small className="text-muted">
                                    <i className="bi bi-info-circle me-1"></i>
                                    Additional details available
                                  </small>
                                </div>
                              )}
                            </div>
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>
                )}

                {/* Contact Information */}
                <div className="case-card contact-card">
                  <div className="case-card-header">
                    <h4>
                      <i className="bi bi-telephone me-2"></i>
                      Need More Information?
                    </h4>
                  </div>
                  <div className="case-card-body">
                    <p className="mb-3">
                      For detailed updates or inquiries about your case, please contact our office:
                    </p>
                    <div className="contact-info">
                      <div className="contact-item">
                        <i className="bi bi-envelope-fill"></i>
                        <a href="mailto:enquiry@naaelahsaleh.co">enquiry@naaelahsaleh.co</a>
                      </div>
                      <div className="contact-item">
                        <i className="bi bi-telephone-fill"></i>
                        <a href="tel:+60193186436">+60 19-318 6436</a>
                      </div>
                      <div className="contact-item">
                        <i className="bi bi-geo-alt-fill"></i>
                        <span>No.6, Jalan Tasik Beringin 1, Taman Sepang Putra, 43950 Sungai Pelek, Selangor</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* FAQ Section */}
          <div className="row mt-5">
            <div className="col-lg-12">
              <div className="faq-section">
                <h4 className="mb-4">
                  <i className="bi bi-question-circle me-2"></i>
                  Frequently Asked Questions
                </h4>
                <div className="accordion" id="faqAccordion">
                  <div className="accordion-item">
                    <h2 className="accordion-header">
                      <button className="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Where can I find my case reference number?
                      </button>
                    </h2>
                    <div id="faq1" className="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                      <div className="accordion-body">
                        Your case reference number is provided in the initial documentation you received when your case was filed with our firm. It follows the format YYYY-MM-XXXXXX (e.g., 2025-08-1APP7I).
                      </div>
                    </div>
                  </div>

                  <div className="accordion-item">
                    <h2 className="accordion-header">
                      <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Why do I need to provide my IC/Passport number?
                      </button>
                    </h2>
                    <div id="faq2" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                      <div className="accordion-body">
                        We require your IC/Passport number for security verification to ensure that only authorized parties can access case information. This protects your privacy and confidentiality.
                      </div>
                    </div>
                  </div>

                  <div className="accordion-item">
                    <h2 className="accordion-header">
                      <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        What information can I see about my case?
                      </button>
                    </h2>
                    <div id="faq3" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                      <div className="accordion-body">
                        You can view your case status, case type, court information, parties involved, and other relevant details. For more detailed information or updates, please contact our office directly.
                      </div>
                    </div>
                  </div>

                  <div className="accordion-item">
                    <h2 className="accordion-header">
                      <button className="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                        Is my information secure?
                      </button>
                    </h2>
                    <div id="faq4" className="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                      <div className="accordion-body">
                        Yes, all information is transmitted securely and is only accessible to verified parties. We take your privacy and data security seriously.
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

