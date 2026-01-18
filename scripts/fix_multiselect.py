
import os

files = [
    'mvc/views/report/transaction/TransactionSummaryView.php',
    'mvc/views/report/purchase/PurchaseReportView.php',
    'mvc/views/report/sale/SaleReportView.php' # Found in earlier grep
]

replacements = [
    (
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js',
        "<?=base_url('assets/vendor/bootstrap-multiselect/js/bootstrap-multiselect.js')?>"
    ),
    (
        'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css',
        "<?=base_url('assets/vendor/bootstrap-multiselect/css/bootstrap-multiselect.css')?>"
    )
]

for file_path in files:
    if os.path.exists(file_path):
        with open(file_path, 'r') as f:
            content = f.read()
        
        orig_content = content
        for search, replace in replacements:
            # Simple string replacement for the URL part
            # But the file probably has <script src="...">
            # I'll rely on replacing the URL string itself.
            content = content.replace(search, replace)
        
        if content != orig_content:
            with open(file_path, 'w') as f:
                f.write(content)
            print(f"Updated {file_path}")
        else:
            print(f"No changes in {file_path}")
