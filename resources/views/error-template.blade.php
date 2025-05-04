<!DOCTYPE html>
<html>
<head>
    <title>Error Report</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .error-title { color: #dc3545; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .error-message { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .error-details { margin-top: 20px; }
        .detail-label { font-weight: bold; margin-right: 5px; }
        pre { background: #f0f0f0; padding: 10px; border-radius: 4px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="error-title">{{ $error->getTitle() }}</h1>
        
        <div class="error-message">
            {{ $error->getMessage() }}
        </div>

        @if ($error->hasException())
        <div class="error-details">
            <div>
                <span class="detail-label">File:</span>
                {{ $error->getException()->getFile() }}:{{ $error->getException()->getLine() }}
            </div>
            
            <div style="margin-top: 15px;">
                <span class="detail-label">Stack Trace:</span>
                <pre>{{ $error->getException()->getTraceAsString() }}</pre>
            </div>
        </div>
        @endif
    </div>
</body>
</html>