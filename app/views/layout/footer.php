    </main>
    <footer class="py-4 bg-dark text-white-50">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <p class="mb-0">&copy; <?= date('Y'); ?> SquadSport. Built for fast match-making.</p>
            <div class="d-flex gap-3">
                <span><i class="bi bi-geo-alt me-1"></i>Amsterdam, NL</span>
                <span><i class="bi bi-envelope me-1"></i>support@squadsport.app</span>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize immediately - before deferred script loads
        (function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            // Match filters
            window.applyMatchFilters = function() {
                const form = document.getElementById('match-filter-form');
                const list = document.getElementById('match-list');
                if (!form || !list) return;
                
                const params = new URLSearchParams();
                params.append('route', 'matches/index');
                params.append('format', 'json');
                
                const sport = form.querySelector('[name="sport"]')?.value;
                const location = form.querySelector('[name="location"]')?.value;
                const date = form.querySelector('[name="date"]')?.value;
                
                if (sport) params.append('sport', sport);
                if (location) params.append('location', location);
                if (date) params.append('date', date);
                
                list.innerHTML = '<div class="text-center py-4"><div class="spinner-border"></div></div>';
                
                fetch('index.php?' + params.toString())
                    .then(r => r.json())
                    .then(data => {
                        if (data && data.html) {
                            list.innerHTML = data.html;
                            if (window.initMatchActions) window.initMatchActions();
                        } else {
                            list.innerHTML = '<div class="alert alert-light">No matches found.</div>';
                        }
                    })
                    .catch(e => {
                        console.error(e);
                        list.innerHTML = '<div class="alert alert-danger">Error loading matches.</div>';
                    });
            };
            
            // Join match
            window.joinMatch = function(matchId, button) {
                if (!csrfToken) {
                    alert('Security token missing. Please refresh.');
                    return;
                }
                
                const card = button.closest('.match-card') || button.closest('article');
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';
                
                fetch('index.php?route=matches/join', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                    body: JSON.stringify({ match_id: parseInt(matchId), csrf_token: csrfToken })
                })
                .then(r => r.json())
                .then(result => {
                    if (result.status === 'success') {
                        const slotsEl = card?.querySelector('.js-match-slots');
                        if (slotsEl && typeof result.participants === 'number') {
                            const max = parseInt(card?.dataset.maxPlayers || '0', 10);
                            slotsEl.textContent = Math.max(0, max - result.participants);
                        }
                        setTimeout(() => {
                            if (window.refreshMatchList) window.refreshMatchList();
                            else window.location.reload();
                        }, 500);
                    } else {
                        alert(result.message || 'Unable to join match.');
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                })
                .catch(e => {
                    console.error(e);
                    alert('Connection error. Please try again.');
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            };
            
            // Leave match
            window.leaveMatch = function(matchId, button) {
                if (!csrfToken) {
                    alert('Security token missing. Please refresh.');
                    return;
                }
                
                const card = button.closest('.match-card') || button.closest('article');
                button.disabled = true;
                const originalText = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';
                
                fetch('index.php?route=matches/leave', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': csrfToken,
                    },
                    body: JSON.stringify({ match_id: parseInt(matchId), csrf_token: csrfToken })
                })
                .then(r => r.json())
                .then(result => {
                    if (result.status === 'success') {
                        const slotsEl = card?.querySelector('.js-match-slots');
                        if (slotsEl && typeof result.participants === 'number') {
                            const max = parseInt(card?.dataset.maxPlayers || '0', 10);
                            slotsEl.textContent = Math.max(0, max - result.participants);
                        }
                        setTimeout(() => {
                            if (window.refreshMatchList) window.refreshMatchList();
                            else window.location.reload();
                        }, 500);
                    } else {
                        alert(result.message || 'Unable to leave match.');
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }
                })
                .catch(e => {
                    console.error(e);
                    alert('Connection error. Please try again.');
                    button.disabled = false;
                    button.innerHTML = originalText;
                });
            };
            
            window.refreshMatchList = window.applyMatchFilters;
            
            // Admin match editing - from data attributes
            window.editMatchFromButton = function(button) {
                const idEl = document.getElementById('match-id');
                const sportEl = document.getElementById('match-sport');
                const locationEl = document.getElementById('match-location');
                const dateEl = document.getElementById('match-date');
                const maxEl = document.getElementById('match-max');
                const minSkillEl = document.getElementById('match-min-skill');
                const maxSkillEl = document.getElementById('match-max-skill');
                const statusEl = document.getElementById('match-status');
                const tournamentEl = document.getElementById('match-tournament');
                const actionEl = document.getElementById('match-action');
                
                if (idEl) idEl.value = button.dataset.matchId || '';
                if (sportEl) sportEl.value = button.dataset.matchSport || '';
                if (locationEl) locationEl.value = button.dataset.matchLocation || '';
                if (dateEl) {
                    const dateValue = button.dataset.matchDate || '';
                    dateEl.value = dateValue ? dateValue.replace(' ', 'T').slice(0, 16) : '';
                }
                if (maxEl) maxEl.value = button.dataset.matchMax || '10';
                if (minSkillEl) minSkillEl.value = button.dataset.matchMinSkill || '1';
                if (maxSkillEl) maxSkillEl.value = button.dataset.matchMaxSkill || '5';
                if (statusEl) statusEl.value = button.dataset.matchStatus || 'open';
                if (tournamentEl) tournamentEl.value = button.dataset.matchTournament || '';
                if (actionEl) actionEl.value = 'update';
                
                // Scroll to form
                const form = document.getElementById('match-admin-form');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                return false;
            };
            
            // Admin match editing - from object
            window.editMatch = function(matchData) {
                if (!matchData || typeof matchData !== 'object') return;
                
                const idEl = document.getElementById('match-id');
                const sportEl = document.getElementById('match-sport');
                const locationEl = document.getElementById('match-location');
                const dateEl = document.getElementById('match-date');
                const maxEl = document.getElementById('match-max');
                const minSkillEl = document.getElementById('match-min-skill');
                const maxSkillEl = document.getElementById('match-max-skill');
                const statusEl = document.getElementById('match-status');
                const tournamentEl = document.getElementById('match-tournament');
                const actionEl = document.getElementById('match-action');
                
                if (idEl) idEl.value = matchData.id || '';
                if (sportEl) sportEl.value = matchData.sport_id || '';
                if (locationEl) locationEl.value = matchData.location_id || '';
                if (dateEl) dateEl.value = matchData.date_time ? matchData.date_time.replace(' ', 'T').slice(0, 16) : '';
                if (maxEl) maxEl.value = matchData.max_players || '10';
                if (minSkillEl) minSkillEl.value = matchData.min_skill_level || '1';
                if (maxSkillEl) maxSkillEl.value = matchData.max_skill_level || '5';
                if (statusEl) statusEl.value = matchData.status || 'open';
                if (tournamentEl) tournamentEl.value = matchData.tournament_id || '';
                if (actionEl) actionEl.value = 'update';
                
                // Scroll to form
                const form = document.getElementById('match-admin-form');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            };
            
            // Admin tournament editing
            window.editTournamentFromButton = function(button) {
                const idEl = document.getElementById('tournament-id');
                const nameEl = document.getElementById('tournament-name');
                const sportEl = document.getElementById('tournament-sport');
                const descriptionEl = document.getElementById('tournament-description');
                const statusEl = document.getElementById('tournament-status');
                const startEl = document.getElementById('tournament-start');
                const endEl = document.getElementById('tournament-end');
                const actionEl = document.getElementById('tournament-action');
                
                if (idEl) idEl.value = button.dataset.tournamentId || '';
                if (nameEl) nameEl.value = button.dataset.tournamentName || '';
                if (sportEl) sportEl.value = button.dataset.tournamentSport || '';
                if (descriptionEl) descriptionEl.value = button.dataset.tournamentDescription || '';
                if (statusEl) statusEl.value = button.dataset.tournamentStatus || 'upcoming';
                if (startEl) startEl.value = button.dataset.tournamentStart || '';
                if (endEl) endEl.value = button.dataset.tournamentEnd || '';
                if (actionEl) actionEl.value = 'update';
                
                // Scroll to form
                const form = document.querySelector('form[method="post"]');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                return false;
            };
            
            // Admin participant result update (immediate, no confirmation)
            window.updateParticipantResult = async function(select) {
                const participantId = select.dataset.participantId;
                const originalValue = select.dataset.originalValue || select.value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
                
                if (!participantId || !csrfToken) {
                    console.error('Missing participant ID or CSRF token');
                    select.value = originalValue;
                    return;
                }
                
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
            };
            
            // Admin event editing
            window.editEventFromButton = function(button) {
                const idEl = document.getElementById('event-id');
                const titleEl = document.getElementById('event-title');
                const descriptionEl = document.getElementById('event-description');
                const startEl = document.getElementById('event-start');
                const venueEl = document.getElementById('event-venue');
                const cityEl = document.getElementById('event-city');
                const linkEl = document.getElementById('event-link');
                const actionEl = document.getElementById('event-action');
                
                if (idEl) idEl.value = button.dataset.eventId || '';
                if (titleEl) titleEl.value = button.dataset.eventTitle || '';
                if (descriptionEl) descriptionEl.value = button.dataset.eventDescription || '';
                if (startEl) {
                    const dateValue = button.dataset.eventStart || '';
                    startEl.value = dateValue ? dateValue.replace(' ', 'T').slice(0, 16) : '';
                }
                if (venueEl) venueEl.value = button.dataset.eventVenue || '';
                if (cityEl) cityEl.value = button.dataset.eventCity || '';
                if (linkEl) linkEl.value = button.dataset.eventLink || '';
                if (actionEl) actionEl.value = 'update';
                
                // Scroll to form
                const form = document.querySelector('form[method="post"]');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                
                return false;
            };
            
            console.log('SquadSport: Core functions initialized');
            console.log('Available functions:', {
                applyMatchFilters: typeof window.applyMatchFilters,
                joinMatch: typeof window.joinMatch,
                leaveMatch: typeof window.leaveMatch,
                refreshMatchList: typeof window.refreshMatchList,
                editMatch: typeof window.editMatch,
                editEventFromButton: typeof window.editEventFromButton
            });
        })();
    </script>
    <script src="<?= asset('js/app.js'); ?>"></script>
</body>
</html>

