"use client";

import { useState } from "react";
import { GoogleReCaptchaProvider, useGoogleReCaptcha } from "react-google-recaptcha-v3";

function ContactForm() {
  const { executeRecaptcha } = useGoogleReCaptcha();
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    department: "",
    subject: "",
    message: "",
  });
  const [attachment, setAttachment] = useState<File | null>(null);
  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();

    if (!executeRecaptcha) {
      setError('reCAPTCHA not ready. Please try again.');
      return;
    }

    setLoading(true);
    setError("");
    setSuccess(false);

    try {
      // Get reCAPTCHA token
      const token = await executeRecaptcha('contact_form');

      // Send to API
      const response = await fetch('/api/contact', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          ...formData,
          recaptchaToken: token,
        }),
      });

      const data = await response.json();

      if (response.ok) {
        setSuccess(true);
        setFormData({ name: "", email: "", department: "", subject: "", message: "" });
        setAttachment(null);
        
        // Reset file input
        const fileInput = document.getElementById('attachment') as HTMLInputElement;
        if (fileInput) fileInput.value = '';
        
        // Hide success message after 5 seconds
        setTimeout(() => setSuccess(false), 5000);
      } else {
        setError(data.error || 'Failed to send message. Please try again.');
      }
    } catch (error) {
      console.error('Form submission error:', error);
      setError('Network error. Please check your connection and try again.');
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    if (e.target.files && e.target.files[0]) {
      setAttachment(e.target.files[0]);
    }
  };

  return (
    <>
      <style jsx>{`
        .custom-select {
          font-family: var(--default-font);
          font-size: 14px;
          color: var(--default-color);
          background-color: #fff;
          padding: 10px 15px;
          border: 1px solid #ddd;
          border-radius: 4px;
          appearance: none;
          background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M10.293 3.293L6 7.586 1.707 3.293A1 1 0 00.293 4.707l5 5a1 1 0 001.414 0l5-5a1 1 0 10-1.414-1.414z'/%3E%3C/svg%3E");
          background-repeat: no-repeat;
          background-position: right 15px center;
          background-size: 12px;
          padding-right: 40px;
          cursor: pointer;
          transition: all 0.3s ease;
        }
        
        .custom-select:focus {
          outline: none;
          border-color: var(--accent-color);
          box-shadow: 0 0 0 0.2rem rgba(0, 131, 116, 0.15);
        }
        
        .custom-select option {
          font-family: var(--default-font);
          font-size: 14px;
          padding: 10px;
          color: var(--default-color);
        }
        
        .custom-select:hover {
          border-color: #bbb;
        }
        
        .file-input-wrapper {
          position: relative;
        }
        
        .file-input {
          font-family: var(--default-font);
          font-size: 14px;
          cursor: pointer;
        }
        
        .file-input::file-selector-button {
          font-family: var(--default-font);
          font-size: 14px;
          padding: 8px 15px;
          background-color: var(--accent-color);
          color: #fff;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          margin-right: 10px;
          transition: all 0.3s ease;
        }
        
        .file-input::file-selector-button:hover {
          background-color: color-mix(in srgb, var(--accent-color), transparent 20%);
        }
        
        .submit-button {
          font-family: var(--default-font);
          font-size: 15px;
          font-weight: 500;
          padding: 12px 40px;
          background-color: var(--accent-color);
          color: #fff;
          border: none;
          border-radius: 4px;
          cursor: pointer;
          transition: all 0.3s ease;
          min-width: 150px;
        }
        
        .submit-button:hover:not(:disabled) {
          background-color: color-mix(in srgb, var(--accent-color), transparent 20%);
          transform: translateY(-2px);
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .submit-button:disabled {
          opacity: 0.6;
          cursor: not-allowed;
        }
      `}</style>
      
      <section id="contact-hero" className="hero section dark-background">
        <div className="container">
          <div className="row">
            <div className="col-lg-12 text-center" data-aos="zoom-out">
              <h1>Contact Us</h1>
              <p>Get in touch with our legal team</p>
            </div>
          </div>
        </div>
      </section>

      <section id="contact" className="contact section">
        <div className="container section-title" data-aos="fade-up">
          <h2>Contact</h2>
          <p>Have a legal question or need consultation? Reach out to us</p>
        </div>

        <div className="container" data-aos="fade-up" data-aos-delay="100">
          <div className="row gy-4">
            <div className="col-lg-3 col-md-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                <i className="bi bi-geo-alt"></i>
                <h3>HQ - Sg Pelek, Selangor</h3>
                <p className="text-center">No.6, Jalan Tasik Beringin 1, Taman Sepang Putra</p>
                <p className="text-center">43950 Sungai Pelek, Selangor</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="250">
                <i className="bi bi-geo-alt"></i>
                <h3>Sendayan Branch, N. Sembilan</h3>
                <p className="text-center">No.59-1, Jalan Metro Sendayan 1/3, Sendayan Metro Park</p>
                <p className="text-center">71950 Bandar Sri Sendayan, Negeri Sembilan</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                <i className="bi bi-telephone"></i>
                <h3>Call Us</h3>
                <p className="mb-2"><strong>HQ:</strong> +60 19-318 6436</p>
                <p className="mb-2"><strong>Litigation:</strong> +60 116-233 9159</p>
                <p><strong>Sendayan:</strong> +60 13-851 1400</p>
              </div>
            </div>

            <div className="col-lg-3 col-md-6">
              <div className="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="350">
                <i className="bi bi-envelope"></i>
                <h3>Email Us</h3>
                <p className="mb-2"><a href="mailto:general@naaelahsaleh.co" style={{color: 'inherit', textDecoration: 'none'}}>general@naaelahsaleh.co</a></p>
                <p className="mb-2"><a href="mailto:litigation@naaelahsaleh.co" style={{color: 'inherit', textDecoration: 'none'}}>litigation@naaelahsaleh.co</a></p>
                <p><a href="mailto:sendayan@naaelahsaleh.co" style={{color: 'inherit', textDecoration: 'none'}}>sendayan@naaelahsaleh.co</a></p>
              </div>
            </div>
          </div>

          <div className="row gy-4 mt-5">
            <div className="col-12">
              <form onSubmit={handleSubmit} className="php-email-form" data-aos="fade-up" data-aos-delay="500">
                <div className="row gy-4">
                  <div className="col-md-6">
                    <input type="text" name="name" className="form-control" placeholder="Your Name" required value={formData.name} onChange={handleChange} />
                  </div>

                  <div className="col-md-6">
                    <input type="email" className="form-control" name="email" placeholder="Your Email" required value={formData.email} onChange={handleChange} />
                  </div>

                  <div className="col-md-12">
                    <select className="form-control custom-select" name="department" required value={formData.department} onChange={handleChange}>
                      <option value="">Select Department</option>
                      <option value="general">General Inquiries (general@naaelahsaleh.co)</option>
                      <option value="litigation">Litigation Matters (litigation@naaelahsaleh.co)</option>
                      <option value="sendayan">Sendayan Branch (sendayan@naaelahsaleh.co)</option>
                    </select>
                  </div>

                  <div className="col-md-12">
                    <input type="text" name="subject" className="form-control" placeholder="Subject" required value={formData.subject} onChange={handleChange} />
                  </div>

                  <div className="col-md-12">
                    <textarea className="form-control" name="message" rows={6} placeholder="Message" required value={formData.message} onChange={handleChange}></textarea>
                  </div>

                  <div className="col-md-6">
                    <div className="file-input-wrapper">
                      <input 
                        type="file" 
                        name="attachment" 
                        id="attachment" 
                        className="form-control file-input" 
                        onChange={handleFileChange}
                        accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                      />
                      {attachment && <small className="text-muted d-block mt-2">Selected: {attachment.name}</small>}
                    </div>
                  </div>

                  <div className="col-md-6 text-end d-flex align-items-start justify-content-end">
                    <button type="submit" disabled={loading} className="submit-button">
                      {loading ? 'Submitting...' : 'Submit'}
                    </button>
                  </div>

                              <div className="col-md-12">
                                {success && (
                                  <div className="alert alert-success d-flex align-items-center" role="alert">
                                    <i className="bi bi-check-circle-fill me-2" style={{fontSize: '20px'}}></i>
                                    <div>
                                      <strong>Success!</strong> Your message has been sent. We will respond shortly. Please check your email for confirmation.
                                    </div>
                                  </div>
                                )}
                                {error && (
                                  <div className="alert alert-danger d-flex align-items-center" role="alert">
                                    <i className="bi bi-exclamation-triangle-fill me-2" style={{fontSize: '20px'}}></i>
                                    <div>
                                      <strong>Error!</strong> {error}
                                    </div>
                                  </div>
                                )}
                              </div>
                </div>
              </form>
            </div>
          </div>

          <div className="row gy-4 mt-4">
            <div className="col-lg-6">
              <div className="mb-2">
                <h5 className="text-center">
                  <i className="bi bi-geo-alt-fill me-2 text-primary"></i>
                  HQ - Sg Pelek, Selangor
                </h5>
              </div>
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.945!2d101.7018994!3d2.6251926!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cd934afdad59a1%3A0x85562048ae8b11c7!2s6%2C%20Jalan%20Tasik%20Beringin%201%2C%20Pantai%20Sepang%20Putra%2C%2043950%20Tanjong%20Sepat%2C%20Selangor!5e0!3m2!1sen!2smy!4v1620000000000!5m2!1sen!2smy" 
                style={{border:0, width: "100%", height: "400px", borderRadius: "8px"}} 
                allowFullScreen 
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
              ></iframe>
              <div className="text-center mt-2">
                <a href="https://maps.app.goo.gl/pUgz2zTDa7Jz6bBG7" target="_blank" rel="noopener noreferrer" className="btn btn-sm btn-outline-primary">
                  <i className="bi bi-arrow-up-right-circle me-1"></i> Open in Google Maps
                </a>
              </div>
            </div>
            
            <div className="col-lg-6">
              <div className="mb-2">
                <h5 className="text-center">
                  <i className="bi bi-geo-alt-fill me-2 text-success"></i>
                  Sendayan Branch, N. Sembilan
                </h5>
              </div>
              <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.234!2d101.8432516!3d2.6858167!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cde9b217f0c3a9%3A0x63f2db2a7781547!2sNAAELAH%20SALEH%20%26%20CO!5e0!3m2!1sen!2smy!4v1620000000000!5m2!1sen!2smy" 
                style={{border:0, width: "100%", height: "400px", borderRadius: "8px"}} 
                allowFullScreen 
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
              ></iframe>
              <div className="text-center mt-2">
                <a href="https://maps.app.goo.gl/RZnUmeTpSqzKB3MX9" target="_blank" rel="noopener noreferrer" className="btn btn-sm btn-outline-success">
                  <i className="bi bi-arrow-up-right-circle me-1"></i> Open in Google Maps
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
    </>
  );
}

export default function Contact() {
  return (
    <GoogleReCaptchaProvider
      reCaptchaKey="6Lccb_krAAAAAB7WYgTKDk0TXk6XgzE__S2aj2DZ"
      scriptProps={{
        async: true,
        defer: true,
        appendTo: "head",
      }}
    >
      <ContactForm />
    </GoogleReCaptchaProvider>
  );
}
