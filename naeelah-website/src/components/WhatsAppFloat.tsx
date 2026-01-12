"use client";

import { useState } from "react";

export default function WhatsAppFloat() {
  const [isOpen, setIsOpen] = useState(false);

  const whatsappNumbers = [
    {
      label: "Sg Pelek/HQ",
      number: "+60193186436",
      description: "General Inquiries",
    },
    {
      label: "Litigation",
      number: "+601162339159",
      description: "Legal Cases",
    },
    {
      label: "Sendayan",
      number: "+60138511400",
      description: "Conveyancing",
    },
  ];

  const openWhatsApp = (number: string) => {
    window.open(`https://wa.me/${number.replace(/[^0-9]/g, '')}`, '_blank');
  };

  return (
    <>
      {/* WhatsApp Menu */}
      {isOpen && (
        <div 
          className="position-fixed" 
          style={{
            bottom: '100px',
            right: '20px',
            zIndex: 9998,
            background: 'white',
            borderRadius: '12px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            minWidth: '280px',
            animation: 'fadeInUp 0.3s ease-out'
          }}
        >
          <div className="p-3 border-bottom">
            <div className="d-flex justify-content-between align-items-center">
              <h6 className="mb-0 fw-bold">Contact Us on WhatsApp</h6>
              <button 
                onClick={() => setIsOpen(false)}
                className="btn-close btn-sm"
                aria-label="Close"
              ></button>
            </div>
          </div>
          <div className="p-2">
            {whatsappNumbers.map((item, index) => (
              <button
                key={index}
                onClick={() => openWhatsApp(item.number)}
                className="btn w-100 text-start mb-2 d-flex align-items-center gap-2 border"
                style={{
                  padding: '12px',
                  borderRadius: '8px',
                  transition: 'all 0.2s',
                  background: 'white',
                }}
                onMouseEnter={(e) => {
                  e.currentTarget.style.background = '#25D366';
                  e.currentTarget.style.color = 'white';
                }}
                onMouseLeave={(e) => {
                  e.currentTarget.style.background = 'white';
                  e.currentTarget.style.color = 'inherit';
                }}
              >
                <i className="bi bi-whatsapp" style={{fontSize: '24px', color: '#25D366'}}></i>
                <div className="flex-grow-1">
                  <div className="fw-bold small">{item.label}</div>
                  <div className="text-muted" style={{fontSize: '11px'}}>{item.description}</div>
                  <div style={{fontSize: '12px'}}>{item.number}</div>
                </div>
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Floating WhatsApp Button */}
      <button
        onClick={() => setIsOpen(!isOpen)}
        className="position-fixed d-flex align-items-center justify-content-center border-0 whatsapp-button"
        style={{
          bottom: '20px',
          right: '20px',
          width: '60px',
          height: '60px',
          borderRadius: '50%',
          background: '#25D366',
          color: 'white',
          fontSize: '28px',
          boxShadow: '0 4px 12px rgba(37, 211, 102, 0.4)',
          zIndex: 9999,
          cursor: 'pointer',
          transition: 'all 0.3s ease',
        }}
        onMouseEnter={(e) => {
          e.currentTarget.style.transform = 'scale(1.1)';
          e.currentTarget.style.boxShadow = '0 6px 16px rgba(37, 211, 102, 0.6)';
        }}
        onMouseLeave={(e) => {
          e.currentTarget.style.transform = 'scale(1)';
          e.currentTarget.style.boxShadow = '0 4px 12px rgba(37, 211, 102, 0.4)';
        }}
        aria-label="WhatsApp"
      >
        <i className="bi bi-whatsapp"></i>
      </button>

      <style jsx>{`
        @keyframes fadeInUp {
          from {
            opacity: 0;
            transform: translateY(20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }
        
        @keyframes pulse {
          0% {
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 0 0 0 rgba(37, 211, 102, 0.7);
          }
          50% {
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 0 0 10px rgba(37, 211, 102, 0);
          }
          100% {
            box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4), 0 0 0 0 rgba(37, 211, 102, 0);
          }
        }
        
        .whatsapp-button {
          animation: pulse 2s infinite;
        }
      `}</style>
    </>
  );
}

