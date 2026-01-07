const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

function initializeApp() {
    console.log('SquadSport: Initializing...');
    console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');
    initSportRows();
    initMatchFilters();
    initMatchActions();
    initAdminForms();
    console.log('SquadSport: Initialization complete');
}

// Run immediately if DOM is ready, otherwise wait
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeApp);
} else {
    // DOM is already ready
    initializeApp();
}

function initSportRows() {
    const addButton = document.getElementById('add-sport-row');
    const template = document.getElementById('sport-row-template');
    const list = document.getElementById('sport-list');

    if (!list) return;

    // Add button is optional (removed from UI per user request)
    if (addButton && template) {
        const appendRow = () => {
            const clone = template.content.cloneNode(true);
            list.appendChild(clone);
        };

        if (!list.querySelector('.sport-row')) {
            appendRow();
        }

        addButton.addEventListener('click', () => {
            appendRow();
        });
    }

    // Still handle remove functionality
    list.addEventListener('click', (event) => {
        if (event.target.classList.contains('js-remove-row')) {
            const rows = list.querySelectorAll('.sport-row');
            if (rows.length <= 1) {
                rows[0].querySelectorAll('select, input').forEach((input) => {
                    input.value = '';
                });
                return;
            }
            event.target.closest('.sport-row')?.remove();
        }
    });
}

function applyMatchFilters() {
    const form = document.getElementById('match-filter-form');
    const list = document.getElementById('match-list');
    
    if (!form || !list) {
        console.warn('Match filters: form or list not found');
        return;
    }

    const params = new URLSearchParams();
    params.append('route', 'matches/index');
    params.append('format', 'json');
    
    const sport = form.querySelector('[name="sport"]')?.value;
    const location = form.querySelector('[name="location"]')?.value;
    const date = form.querySelector('[name="date"]')?.value;
    
    if (sport) params.append('sport', sport);
    if (location) params.append('location', location);
    if (date) params.append('date', date);
    
    const url = `index.php?${params.toString()}`;
    console.log('Fetching filtered matches:', url);
    
    list.innerHTML = '<div class="text-center py-4"><div class="spinner-border" role="status"></div></div>';
    
    fetch(url)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            if (data && data.html) {
                list.innerHTML = data.html;
                initMatchActions();
            } else {
                list.innerHTML = '<div class="alert alert-light border text-center">No matches found.</div>';
            }
        })
        .catch((error) => {
            console.error('Filter error:', error);
            list.innerHTML = '<div class="alert alert-danger border text-center">Error loading matches. Please refresh.</div>';
        });
}

function initMatchFilters() {
    const form = document.getElementById('match-filter-form');
    const list = document.getElementById('match-list');
    
    if (!form || !list) {
        console.warn('Match filters: form or list not found', { form: !!form, list: !!list });
        return;
    }

    console.log('Initializing match filters...');
    
    window.applyMatchFilters = applyMatchFilters;
    window.refreshMatchList = applyMatchFilters;
    
    // Prevent default form submission
    form.onsubmit = (e) => {
        e.preventDefault();
        e.stopPropagation();
        applyMatchFilters();
        return false;
    };
    
    // Add change listeners (backup for inline handlers)
    ['select', 'input[type="date"]'].forEach(selector => {
        form.querySelectorAll(selector).forEach(el => {
            el.addEventListener('change', applyMatchFilters);
            if (el.type === 'date') {
                el.addEventListener('input', applyMatchFilters);
            }
        });
    });
    
    console.log('Match filters initialized successfully');
}

async function joinMatch(matchId, button) {
    if (!csrfToken) {
        alert('Security token missing. Please refresh the page.');
        return;
    }

    const card = button.closest('.match-card') || button.closest('article');
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';

    try {
        const response = await fetch(`index.php?route=matches/join`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify({ 
                match_id: parseInt(matchId, 10),
                csrf_token: csrfToken 
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const result = await response.json();

        if (result.status === 'success') {
            const slotsElement = card?.querySelector('.js-match-slots');
            if (slotsElement && typeof result.participants === 'number') {
                const maxPlayers = parseInt(card?.dataset.maxPlayers || '0', 10);
                const availableSlots = Math.max(0, maxPlayers - result.participants);
                slotsElement.textContent = availableSlots;
            }
            
            setTimeout(() => {
                if (typeof window.refreshMatchList === 'function') {
                    window.refreshMatchList();
                } else {
                    window.location.reload();
                }
            }, 500);
        } else {
            alert(result.message || 'Unable to join match.');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Join error:', error);
        alert('Connection error. Please try again.');
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

async function leaveMatch(matchId, button) {
    if (!csrfToken) {
        alert('Security token missing. Please refresh the page.');
        return;
    }

    const card = button.closest('.match-card') || button.closest('article');
    button.disabled = true;
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';

    try {
        const response = await fetch(`index.php?route=matches/leave`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken,
            },
            body: JSON.stringify({ 
                match_id: parseInt(matchId, 10),
                csrf_token: csrfToken 
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }

        const result = await response.json();

        if (result.status === 'success') {
            const slotsElement = card?.querySelector('.js-match-slots');
            if (slotsElement && typeof result.participants === 'number') {
                const maxPlayers = parseInt(card?.dataset.maxPlayers || '0', 10);
                const availableSlots = Math.max(0, maxPlayers - result.participants);
                slotsElement.textContent = availableSlots;
            }
            
            setTimeout(() => {
                if (typeof window.refreshMatchList === 'function') {
                    window.refreshMatchList();
                } else {
                    window.location.reload();
                }
            }, 500);
        } else {
            alert(result.message || 'Unable to leave match.');
            button.disabled = false;
            button.innerHTML = originalText;
        }
    } catch (error) {
        console.error('Leave error:', error);
        alert('Connection error. Please try again.');
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

function initMatchActions() {
    const list = document.getElementById('match-list');
    if (!list) {
        console.warn('Match actions: list not found');
        return;
    }

    console.log('Initializing match actions...');
    
    // Make functions globally available for inline handlers
    window.joinMatch = joinMatch;
    window.leaveMatch = leaveMatch;
    
    // Use event delegation as backup
    list.onclick = async (event) => {
        const joinBtn = event.target.closest('.js-join-match');
        const leaveBtn = event.target.closest('.js-leave-match');
        
        if (joinBtn && !joinBtn.onclick) {
            const matchId = joinBtn.dataset.matchId;
            if (matchId) joinMatch(matchId, joinBtn);
        } else if (leaveBtn && !leaveBtn.onclick) {
            const matchId = leaveBtn.dataset.matchId;
            if (matchId) leaveMatch(matchId, leaveBtn);
        }
    };
    
    console.log('Match actions initialized successfully');
}

// editMatch functions are defined in footer script, but keep compatibility here
if (!window.editMatchFromButton) {
    window.editMatchFromButton = function(button) {
        if (window.editMatchFromButton && typeof window.editMatchFromButton === 'function') {
            return window.editMatchFromButton(button);
        }
        console.warn('editMatchFromButton not available');
        return false;
    };
}

function initAdminForms() {
    document.querySelectorAll('.js-edit-sport').forEach((button) => {
        button.addEventListener('click', () => {
            const data = JSON.parse(button.closest('tr').dataset.sport);
            document.getElementById('sport-id').value = data.id;
            document.getElementById('sport-name').value = data.name || '';
            document.getElementById('sport-description').value = data.description || '';
            document.getElementById('sport-action').value = 'update';
        });
    });
    const sportReset = document.getElementById('sport-reset');
    sportReset?.addEventListener('click', () => {
        document.getElementById('sport-id').value = '';
        document.getElementById('sport-name').value = '';
        document.getElementById('sport-description').value = '';
        document.getElementById('sport-action').value = 'create';
    });

    document.querySelectorAll('.js-edit-location').forEach((button) => {
        button.addEventListener('click', () => {
            const data = JSON.parse(button.closest('tr').dataset.location);
            document.getElementById('location-id').value = data.id;
            document.getElementById('location-name').value = data.name || '';
            document.getElementById('location-address').value = data.address || '';
            document.getElementById('location-city').value = data.city || '';
            document.getElementById('location-action').value = 'update';
        });
    });
    const locationReset = document.getElementById('location-reset');
    locationReset?.addEventListener('click', () => {
        ['location-id', 'location-name', 'location-address', 'location-city'].forEach((id) => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        document.getElementById('location-action').value = 'create';
    });

    document.querySelectorAll('.js-edit-match').forEach((button) => {
        button.addEventListener('click', (e) => {
            // If inline handler already handled it, skip
            if (e.target.onclick && window.editMatchFromButton) {
                window.editMatchFromButton(button);
                return;
            }
            
            // Fallback: try data attributes
            if (button.dataset.matchId) {
                if (window.editMatchFromButton) {
                    window.editMatchFromButton(button);
                    return;
                }
            }
            
            // Fallback: try JSON from row
            const row = button.closest('tr');
            const matchData = row?.dataset.match;
            if (matchData) {
                try {
                    const data = JSON.parse(matchData);
                    if (window.editMatch) {
                        window.editMatch(data);
                    }
                } catch (err) {
                    console.error('Error parsing match data:', err);
                }
            }
        });
    });
    document.getElementById('match-reset')?.addEventListener('click', () => {
        document.getElementById('match-id').value = '';
        document.getElementById('match-sport').value = '';
        document.getElementById('match-location').value = '';
        document.getElementById('match-date').value = '';
        document.getElementById('match-max').value = '10';
        document.getElementById('match-min-skill').value = '1';
        document.getElementById('match-max-skill').value = '5';
        document.getElementById('match-status').value = 'open';
        document.getElementById('match-tournament').value = '';
        document.getElementById('match-action').value = 'create';
    });

    document.querySelectorAll('.js-edit-tournament').forEach((button) => {
        button.addEventListener('click', (e) => {
            // If inline handler already handled it, skip
            if (e.target.onclick && window.editTournamentFromButton) {
                window.editTournamentFromButton(button);
                return;
            }
            
            // Fallback: try data attributes
            if (button.dataset.tournamentId) {
                if (window.editTournamentFromButton) {
                    window.editTournamentFromButton(button);
                    return;
                }
            }
            
            // Fallback: try JSON from row
            const row = button.closest('tr');
            const tournamentData = row?.dataset.tournament;
            if (tournamentData) {
                try {
                    const data = JSON.parse(tournamentData);
                    document.getElementById('tournament-id').value = data.id || '';
                    document.getElementById('tournament-name').value = data.name || '';
                    document.getElementById('tournament-sport').value = data.sport_id || '';
                    document.getElementById('tournament-description').value = data.description || '';
                    document.getElementById('tournament-status').value = data.status || 'upcoming';
                    document.getElementById('tournament-start').value = data.start_date || '';
                    document.getElementById('tournament-end').value = data.end_date || '';
                    document.getElementById('tournament-action').value = 'update';
                } catch (err) {
                    console.error('Error parsing tournament data:', err);
                }
            }
        });
    });
    document.getElementById('tournament-reset')?.addEventListener('click', () => {
        document.getElementById('tournament-id').value = '';
        document.getElementById('tournament-name').value = '';
        document.getElementById('tournament-sport').value = '';
        document.getElementById('tournament-description').value = '';
        document.getElementById('tournament-status').value = 'upcoming';
        document.getElementById('tournament-start').value = '';
        document.getElementById('tournament-end').value = '';
        document.getElementById('tournament-action').value = 'create';
    });

    document.querySelectorAll('.js-edit-event').forEach((button) => {
        button.addEventListener('click', (e) => {
            // If inline handler already handled it, skip
            if (e.target.onclick && window.editEventFromButton) {
                window.editEventFromButton(button);
                return;
            }
            
            // Fallback: try data attributes
            if (button.dataset.eventId) {
                if (window.editEventFromButton) {
                    window.editEventFromButton(button);
                    return;
                }
            }
            
            // Fallback: try JSON from row
            const raw = button.closest('tr')?.getAttribute('data-event') || '{}';
            let data = {};
            try {
                data = JSON.parse(raw);
            } catch (error) {
                console.error('Unable to parse event payload', error);
                return;
            }
            
            document.getElementById('event-id').value = data.id || '';
            document.getElementById('event-title').value = data.title || '';
            document.getElementById('event-description').value = data.description || '';
            document.getElementById('event-start').value = data.start_at ? data.start_at.replace(' ', 'T').slice(0, 16) : '';
            document.getElementById('event-venue').value = data.venue || '';
            document.getElementById('event-city').value = data.city || '';
            document.getElementById('event-link').value = data.link || '';
            document.getElementById('event-action').value = 'update';
        });
    });
    document.getElementById('event-reset')?.addEventListener('click', () => {
        document.getElementById('event-id').value = '';
        document.getElementById('event-title').value = '';
        document.getElementById('event-description').value = '';
        document.getElementById('event-start').value = '';
        document.getElementById('event-venue').value = '';
        document.getElementById('event-city').value = '';
        document.getElementById('event-link').value = '';
        document.getElementById('event-action').value = 'create';
    });

    // Handle participant result changes immediately (no confirmation)
    document.querySelectorAll('.participant-result').forEach((select) => {
        select.addEventListener('change', async () => {
            const participantId = select.dataset.participantId;
            if (!participantId || !csrfToken) {
                console.error('Missing participant ID or CSRF token');
                return;
            }
            
            const originalValue = select.dataset.originalValue || select.value;
            select.disabled = true;
            
            try {
                const response = await fetch('index.php?route=admin/approveResult', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                    body: JSON.stringify({
                        participant_id: parseInt(participantId, 10),
                        result: select.value,
                        csrf_token: csrfToken,
                    }),
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                
                const data = await response.json();
                if (data.status === 'success') {
                    select.dataset.originalValue = select.value;
                    // Show visual feedback
                    select.style.backgroundColor = '#d4edda';
                    setTimeout(() => {
                        select.style.backgroundColor = '';
                    }, 1000);
                } else {
                    // Revert on error
                    select.value = originalValue;
                    alert(data.message || 'Unable to update result');
                }
            } catch (error) {
                console.error('Error updating result:', error);
                select.value = originalValue;
                alert('Network error while updating result.');
            } finally {
                select.disabled = false;
            }
        });
    });
}

