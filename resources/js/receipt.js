"use strict";

function printReceipt(orderId) {
    const receiptContent = document.getElementById(
        `receipt-content-${orderId}`
    ).innerHTML;
    const printWindow = window.open("", "_blank");
    printWindow.document.open();
    printWindow.document.write(`
                <html>
                <head>
                    <title>Order Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        h1 { text-align: center; font-size: 24px; }
                        p, h3 { margin: 5px 0; }
                        hr { margin: 15px 0; }
                        ul { padding: 0; list-style-type: none; }
                    </style>
                </head>
                <body>${receiptContent}</body>
                </html>
            `);
    printWindow.document.close();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 100);
}
