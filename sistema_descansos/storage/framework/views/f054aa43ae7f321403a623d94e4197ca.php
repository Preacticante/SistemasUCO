<style>
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background-color: #f3f4f6; 
        margin: 0; 
    }

    .navbar { 
        background-color: #1f2937; 
        color: white; 
        padding: 15px 20px; 
        display: flex; 
        justify-content: space-between; 
        align-items: center; }
    .navbar a { 
        color: #f87171; 
        text-decoration: none; 
        font-weight: bold; }

    .container { 
        max-width: 1100px; 
        margin: 30px auto; 
        background: white; 
        padding: 20px; 
        border-radius: 8px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1); }

    h1 { 
        color: #374151; 
        font-size: 35px; 
        border-bottom: 2px solid #3fbe3f; 
        padding-bottom: 30px; 
        margin-bottom: 40px;
        text-align: center;}

    .cards { 
        display: grid; 
        grid-template-columns: repeat(4, minmax(0, 1fr)); 
        gap: 16px; 
        margin-bottom: 24px; }

    .stat-card { 
        background: #f9fafb; 
        padding: 18px; 
        border-radius: 10px; 
        border: 1px solid #e5e7eb; }
        
    .stat-card strong { 
        display: block; 
        margin-bottom: 10px; 
        color: #374151;
        font-size: 25px;
        text-align: center; }

    .alert-table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 12px; }

    .alert-table th, .alert-table td { 
        text-align: left; 
        padding: 10px; 
        border-bottom: 1px solid #e5e7eb; }

    .alert-table th { 
        background: #f3f4f6; 
        color: #374151; }

    table { 
        width: 100%; 
        border-collapse: collapse; 
        margin-top: 50px;
        background: white; 
        border-radius: 8px; 
        border: 1px solid #3fbe3f; }

    th, td { 
        text-align: left; 
        padding: 12px; 
        border-bottom: 1px solid #e5e7eb; }

    th { 
        background-color: #f9fafb; 
        color: #4b5563; }
    .btn-calcular { 
        background-color: #10b981; 
        color: white; padding: 6px 12px; 
        border-radius: 4px; 
        text-decoration: none; 
        font-size: 14px; }
    .btn-calcular:hover { 
        background-color: #059669; }
</style>
<?php /**PATH C:\Users\becario.tie\Documents\GitHub\SistemasUCO\sistema_descansos\resources\views/dashboard/styles.blade.php ENDPATH**/ ?>