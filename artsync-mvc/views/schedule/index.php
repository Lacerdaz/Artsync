<?php require __DIR__ . '/../layouts/header.php'; // Carrega header e menu ?>

<header class="main-header">
    <h2>Minha Agenda</h2>
    <p>Organize seus shows, ensaios e compromissos.</p>
</header>

<?php if (isset($feedback)): ?>
    <div class="<?php echo $feedback['type'] === 'error' ? 'error-message' : 'success-message'; ?>">
        <?php echo htmlspecialchars($feedback['message']); ?>
    </div>
<?php endif; ?>

<div class="card">
    <h3>Agendar Novo Evento</h3>
    <form action="/schedule/create" method="post">
        <div class="input-group">
            <label for="event_title">Título do Evento</label>
            <input type="text" id="event_title" name="event_title" required>
        </div>
        <div class="input-group">
            <label for="event_date">Data e Hora</label>
            <input type="datetime-local" id="event_date" name="event_date" required> 
        </div>
        <div class="input-group">
            <label for="notes">Anotações (opcional)</label>
            <textarea id="notes" name="notes" rows="3"></textarea>
        </div>
        <button type="submit" name="add_event" class="btn">Agendar</button>
    </form>
</div>

<div class="card">
    <h3>Meus Compromissos</h3>
    <div class="schedule-list">
        <?php if (empty($events)): // $events é passado pelo ScheduleController ?>
            <p>Você não tem nenhum evento agendado.</p>
        <?php else: ?>
            <?php foreach ($events as $event): // Loop pelos eventos ?>
                <div class="schedule-item">
                    <div class="item-date">
                        <span class="day"><?php echo date('d', strtotime($event->eventDate)); ?></span>
                        <span class="month"><?php echo date('M', strtotime($event->eventDate)); ?></span>
                    </div>
                    <div class="item-details">
                        <h4><?php echo htmlspecialchars($event->title); ?></h4>
                        <p><strong>Quando:</strong> <?php echo date('d/m/Y \à\s H:i', strtotime($event->eventDate)); ?></p>
                        <?php if (!empty($event->notes)): ?>
                            <p><strong>Anotações:</strong> <?php echo htmlspecialchars($event->notes); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="item-action">
                        <a href="/schedule/delete?id=<?php echo $event->id; ?>" 
                           class="btn-delete" 
                           onclick="return confirm('Tem certeza que deseja excluir este evento?');">
                            Excluir
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; // Carrega o final do HTML ?>