@extends('crudbooster::admin_template')

@section('content')

<style>
 .breadcrumb{display: none;}
.ai-settings-container { padding:0px 15px; margin-top:-40px;}
.ai-settings-container .panel-stat .panel-heading { font-weight:600; }
.ai-settings-container .stat-value { font-size:24px; font-weight:700; }
.ai-settings-container .api-key-mask { font-family:monospace; }
.ai-settings-container .form-section-title { margin-top:0; margin-bottom:15px; font-weight:700; }
.ai-settings-container .panel-form { border-left:4px solid #5bc0de; }
.ai-settings-container .panel-stats { border-left:4px solid #5cb85c; }
.ai-settings-container .token-limit-note { font-size:12px; color:#777; }
.ai-settings-container .table>thead>tr>th { white-space:nowrap; }
.ai-settings-container .label-on { background:#5cb85c; }
.ai-settings-container .label-off { background:#d9534f; }
.ai-settings-container .help-block.small { font-size:12px; }
</style>

<div class="ai-settings-container">

  <!-- Page Header -->
  <div class="row">
    <div class="col-sm-12">
      <h3 style="margin-top:0;">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
        AI Content Settings & Usage
      </h3>
      <p class="text-muted">Manage AI content generation settings and review statistics and recent API logs.</p>
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      @if($errors ?? false)
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul style="margin-bottom:0;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      @endif
    </div>
  </div>

  <!-- Settings + Token Usage -->
  <div class="row">
    <!-- Settings Form -->
    <div class="col-sm-6">
      <div class="panel panel-info panel-form">
        <div class="panel-heading">AI Settings</div>
        <div class="panel-body">
          <form method="POST" action="{{ $updateAction ?? url(config('crudbooster.ADMIN_PATH').'/ai/settings/update') }}">
            {{ csrf_field() }}
            <div class="form-group">
              <label>Using AI Features</label>
              <select class="form-control" name="using_ai_features">
                <option value="On"  @if(($settings['using_ai_features'] ?? 'Off') === 'On') selected @endif>On</option>
                <option value="Off" @if(($settings['using_ai_features'] ?? 'Off') === 'Off') selected @endif>Off</option>
              </select>
              <p class="help-block small">Turning this off disables all generation, improvement, and translation operations.</p>
            </div>

            @if (CRUDBooster::isSuperadmin())
            <div class="form-group">
              <label>Maximum Token Usage Limit</label>
              <input type="number" min="0" class="form-control" name="maximum_token_usage_limit"
                     value="{{ $settings['maximum_token_usage_limit'] ?? '' }}" placeholder="e.g. 100000">
              <p class="help-block small">Leave empty for unlimited. Used to calculate token usage progress.</p>
            </div>
            @endif

            <div class="form-group">
              <label>Personal OpenAI API Key</label>
              <div class="input-group">
                <input type="password" class="form-control" id="api_key" name="personal_openai_api_key"
                       value="{{ $settings['personal_openai_api_key'] ?? '' }}" placeholder="sk-...">
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" id="toggleKey">
                    <span class="glyphicon glyphicon-eye-open"></span>
                  </button>
                </span>
              </div>
              <p class="help-block small">The full key is never displayed. Save changes to update.</p>
            </div>
            @if (CRUDBooster::isSuperadmin())
            <div class="form-group">
              <label>Company Name</label>
              <input type="text" class="form-control" name="company_name"
                     value="{{ $settings['company_name'] ?? '' }}" placeholder="e.g. Company Name" required>
            </div>

            
            <div class="form-group">
              <label>Company Description</label>
              <textarea class="form-control" name="company_description" rows="3"
                        placeholder="Short company description">{{ $settings['company_description'] ?? '' }}</textarea>
            </div>
            

            <div class="form-group">
              <label>Website Type</label>
              <input type="text" class="form-control" name="website_type"
                     value="{{ $settings['website_type'] ?? '' }}" placeholder="e.g. Business / Technology & Web / Education" required>
            </div>
            @endif

            <button type="submit" class="btn btn-info">
              <span class="glyphicon glyphicon-floppy-disk"></span> Save Settings
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- Usage & Stats -->
    <div class="col-sm-6">
      <div class="panel panel-success panel-stats">
        <div class="panel-heading">Token Usage</div>
        <div class="panel-body">
          @php
            $currentTokens = (int)($stats['current_token_usage'] ?? 0);
            $limitTokens   = (int)($settings['maximum_token_usage_limit'] ?? 0);
            $hasLimit      = $limitTokens > 0;
            $percent       = $hasLimit ? min(100, round(($currentTokens / max(1,$limitTokens)) * 100)) : 0;
          @endphp

          <div class="clearfix" style="margin-bottom:5px;">
            <div class="pull-left">
              <strong>Current:</strong> {{ number_format($currentTokens) }}
            </div>
            <div class="pull-right">
              <strong>Limit:</strong>
              @if($hasLimit)
                {{ number_format($limitTokens) }}
              @else
                <span class="text-muted">Unlimited</span>
              @endif
            </div>
          </div>

          <div class="progress" style="margin-bottom:8px;">
            <div class="progress-bar progress-bar-success" role="progressbar"
                 aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"
                 style="width: {{ $hasLimit ? $percent : 100 }}%;">
              {{ $hasLimit ? $percent.'%' : 'No Limit' }}
            </div>
          </div>
          <div class="token-limit-note">
            Usage is calculated from <code>Ai Content Logs</code> records.
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Generated SEO -->
        <div class="col-xs-6">
          <div class="panel panel-default panel-stat">
            <div class="panel-heading">Generated SEO</div>
            <div class="panel-body">
              <div class="stat-value">{{ number_format($stats['generated_seo'] ?? 0) }}</div>
              <div class="text-muted">SEO generations</div>
            </div>
          </div>
        </div>
        <!-- Generated Module Items -->
        <div class="col-xs-6">
          <div class="panel panel-default panel-stat">
            <div class="panel-heading">Generated Module Items</div>
            <div class="panel-body">
              <div class="stat-value">{{ number_format($stats['generated_module_item'] ?? 0) }}</div>
              <div class="text-muted">Module items generated</div>
            </div>
          </div>
        </div>
        <!-- Improved Content -->
        <div class="col-xs-6">
          <div class="panel panel-default panel-stat">
            <div class="panel-heading">Improved Content</div>
            <div class="panel-body">
              <div class="stat-value">{{ number_format($stats['improved_content'] ?? 0) }}</div>
              <div class="text-muted">Content improvements</div>
            </div>
          </div>
        </div>
        <!-- Translated Content -->
        <div class="col-xs-6">
          <div class="panel panel-default panel-stat">
            <div class="panel-heading">Translated Content</div>
            <div class="panel-body">
              <div class="stat-value">{{ number_format($stats['translated_content'] ?? 0) }}</div>
              <div class="text-muted">Translations</div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Logs: Last 10 Records -->
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          Last 10 Records from <code>Ai Content Logs</code>
          
          {{-- <div class="pull-right">
            <a class="btn btn-xs btn-default" href="{{ url('/ai/logs') }}">
              <span class="glyphicon glyphicon-list-alt"></span> View All
            </a>
          </div>
           --}}
          <div class="clearfix"></div>
        </div>
        <div class="panel-body" style="overflow:auto;">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>API Key</th>
                <th>Prompt Tokens</th>
                <th>Completion Tokens</th>
                <th>Total Tokens</th>
                <th>Created At</th>
              </tr>
            </thead>
            <tbody>
              @forelse(($logs ?? []) as $log)
                <tr>
                  <td class="api-key-mask" title="Partial key shown">
                    @php
                      $k = (string)($log->ai_api_key ?? '');
                    @endphp
                    {{ $k }}
                  </td>
                  <td>{{ number_format($log->usage_prompt_tokens ?? 0) }}</td>
                  <td>{{ number_format($log->usage_completion_tokens ?? 0) }}</td>
                  <td><strong>{{ number_format($log->usage_total_tokens ?? 0) }}</strong></td>
                  <td>{{ $log->created_at }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center text-muted">No records found.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
        <div class="panel-footer">
          <small class="text-muted">
            Data source: <code>Ai Content Logs</code> table (latest 10 by <code>created_at</code>).
          </small>
        </div>
      </div>
    </div>
  </div>

</div>


@endsection

@push('bottom')
   <script>
    (function(){
        // Toggle show/hide API key field
        var btn = document.getElementById('toggleKey');
        var input = document.getElementById('api_key');
        if(btn && input){
        btn.addEventListener('click', function(){
            input.type = input.type === 'password' ? 'text' : 'password';
        });
        }
    })();
</script>
@endpush