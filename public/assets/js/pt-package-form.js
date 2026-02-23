/**
 * PT Package create/edit form: rate per session, commission, dynamic exercises.
 */
(function() {
    function exerciseHtml(i) {
        return '<div class="border rounded p-3 mb-3 exercise-item"><div class="d-flex justify-content-between mb-2"><strong>Exercise #' + (i + 1) + '</strong><button type="button" class="btn btn-sm btn-danger remove-exercise">Remove</button></div>' +
            '<div class="row g-2"><div class="col-md-6"><label class="form-label">Exercise Name</label><input type="text" name="exercises[' + i + '][exercise_name]" class="form-control"></div>' +
            '<div class="col-md-2"><label class="form-label">Sets</label><input type="number" min="0" name="exercises[' + i + '][sets]" class="form-control"></div>' +
            '<div class="col-md-2"><label class="form-label">Reps</label><input type="number" min="0" name="exercises[' + i + '][reps]" class="form-control"></div>' +
            '<div class="col-md-2"><label class="form-label">Weight</label><input type="number" step="0.01" min="0" name="exercises[' + i + '][weight]" class="form-control"></div>' +
            '<div class="col-md-3"><label class="form-label">Duration (min)</label><input type="number" min="0" name="exercises[' + i + '][duration_minutes]" class="form-control"></div>' +
            '<div class="col-md-3"><label class="form-label">Rest (sec)</label><input type="number" min="0" name="exercises[' + i + '][rest_period_seconds]" class="form-control"></div>' +
            '<div class="col-md-6"><label class="form-label">Notes</label><input type="text" name="exercises[' + i + '][notes]" class="form-control"></div></div></div>';
    }

    function updateRatePerSession() {
        var rateEl = document.getElementById('package_rate');
        var countEl = document.getElementById('session_count');
        var rpsEl = document.getElementById('rate_per_session');
        if (!rateEl || !countEl || !rpsEl) return;
        var rate = parseFloat(rateEl.value) || 0;
        var count = parseInt(countEl.value, 10) || 1;
        if (count > 0 && !rpsEl.dataset.manual) rpsEl.value = (rate / count).toFixed(2);
    }

    function updateCommissionPerSession() {
        var rpsEl = document.getElementById('rate_per_session');
        var pctEl = document.getElementById('commission_percentage');
        var cpsEl = document.getElementById('commission_per_session');
        if (!rpsEl || !pctEl || !cpsEl) return;
        var rps = parseFloat(rpsEl.value) || 0;
        var pct = parseFloat(pctEl.value) || 0;
        if (!cpsEl.dataset.manual) cpsEl.value = (rps * pct / 100).toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        var container = document.getElementById('exercises-container');
        var addBtn = document.getElementById('add-exercise');
        if (!container || !addBtn) return;

        var exerciseCount = container.querySelectorAll('.exercise-item').length;

        var rateEl = document.getElementById('package_rate');
        var countEl = document.getElementById('session_count');
        var rpsEl = document.getElementById('rate_per_session');
        var pctEl = document.getElementById('commission_percentage');
        var cpsEl = document.getElementById('commission_per_session');
        if (rateEl) rateEl.addEventListener('input', function() { updateRatePerSession(); updateCommissionPerSession(); });
        if (countEl) countEl.addEventListener('input', function() { updateRatePerSession(); updateCommissionPerSession(); });
        if (rpsEl) rpsEl.addEventListener('input', function() { rpsEl.dataset.manual = '1'; updateCommissionPerSession(); });
        if (pctEl) pctEl.addEventListener('input', function() { updateCommissionPerSession(); });
        if (cpsEl) cpsEl.addEventListener('input', function() { cpsEl.dataset.manual = '1'; });

        addBtn.addEventListener('click', function() {
            container.insertAdjacentHTML('beforeend', exerciseHtml(exerciseCount));
            exerciseCount++;
        });

        container.addEventListener('click', function(e) {
            if (e.target.closest('.remove-exercise')) e.target.closest('.exercise-item').remove();
        });
    });
})();
