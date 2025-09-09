<style>
/*
 * GLOBAL STYLES FOR REPORTS
 */
@page {
    margin: 20mm 15mm 20mm 15mm; /* Top, Right, Bottom, Left */
}
body {
    font-family: 'DejaVu Sans', sans-serif;
    font-size: 10px;
    line-height: 1.4;
    color: #333;
    margin: 0;
    padding: 0;
}

/*
 * HEADER
 */
.header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 70mm;
    background: white;
    z-index: 1000;
    padding-bottom: 0.25mm;
    margin-bottom: 0.25mm;
    margin-top: -3mm;
}

body {
    margin-top: 70mm; /* Space for fixed header */
}

.header-content {
    padding: 0;
    margin-top: 0;
}

.company-info {
    float: left;
    width: 70%;
}
.company-logo {
    float: left;
    margin-right: 10mm;
    width: 30mm;
}
.company-logo img {
    width: 100%;
    height: auto;
}
.contact-label {
    display: inline-block;
    width: 70pt;
    text-align: left;
    vertical-align: baseline;
}
.contact-separator {
    display: inline-block;
    width: 5pt;
    text-align: left;
    vertical-align: baseline;
}
.contact-value {
    display: inline-block;
    vertical-align: baseline;
}
.report-label {
    display: inline-block;
    width: 100pt;
    text-align: left;
    vertical-align: baseline;
}
.report-separator {
    display: inline-block;
    width: 5pt;
    text-align: left;
    vertical-align: baseline;
}
.report-value {
    display: inline-block;
    vertical-align: baseline;
}

.page-counter::before {
    content: counter(page);
}
.report-title {
    text-align: center;
    margin-top: 0.5mm;
    margin-bottom: 1.5mm;
    font-size: 14pt;
    font-weight: bold;
    clear: both;
}
.report-details {
    overflow: auto;
    margin-bottom: 1mm !important;
}
.report-info {
    width: 48%;
    float: left;
    margin-bottom: 1mm;
}
.report-meta {
    float: right;
    text-align: left;
    margin-bottom: 1mm;
    margin-right: 0;
    width: auto;
}
.clear {
    clear: both;
}

/*
 * REPORT TABLE
 */
.report-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2mm;
    margin-bottom: 5mm;
    page-break-inside: auto;
}

.report-table th, .report-table td {
    padding: 4pt 6pt;
    text-align: left;
    vertical-align: top;
    font-size: 10px;
    border: 1px solid #d1d5db;
}
.report-table th {
    background-color: #f9fafb;
    font-weight: bold;
    color: #374151;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    font-size: 9px;
    text-align: center !important;
    vertical-align: middle !important;
}
.report-table thead {
    display: table-header-group;
}
.report-table tbody {
    display: table-row-group;
}

.text-right {
    text-align: right !important;
}
.text-center {
    text-align: center !important;
}
.text-left {
    text-align: left !important;
}

.font-bold {
    font-weight: bold !important;
}

/*
 * PRINT SPECIFIC
 */
@media print {
    .header {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        height: 70mm !important;
        background: white !important;
        z-index: 1000 !important;
        padding: 5mm 15mm 2mm 15mm !important;
        box-sizing: border-box !important;
    }
    
    body {
        margin-top: 70mm !important;
        padding: 0 !important;
        box-sizing: border-box !important;
    }
    
    .report-table thead {
        display: table-header-group !important;
    }
}
</style>
