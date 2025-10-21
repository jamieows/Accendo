<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<main class="dashboard-content">
  <div class="welcome-card">
    <span class="wave">💬</span>
    <div class="welcome-text">Chat with AI</div>
  </div>

  <section class="chat-section">
    <div class="chat-box">
      <div class="messages" id="chatMessages">
        <div class="message ai">
          <div class="message-content">
            👋 Hello! Welcome back to Accendo LMS.<br>
            Would you like to continue your last lesson on <strong>Computer Fundamentals</strong>?
          </div>
          <span class="timestamp">Just now</span>
        </div>
      </div>

      <form class="chat-input" id="chatForm">
        <input type="text" id="userMessage" placeholder="Type your message here..." required />
        <button type="submit">Send</button>
      </form>
    </div>
  </section>
</main>

<?php include('includes/footer.php'); ?>

<script>
  // Simple chat mockup (frontend only)
  const form = document.getElementById('chatForm');
  const input = document.getElementById('userMessage');
  const messages = document.getElementById('chatMessages');

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    const text = input.value.trim();
    if (text === '') return;

    // Add user message
    const userMsg = document.createElement('div');
    userMsg.classList.add('message', 'user');
    userMsg.innerHTML = `<div class="message-content">${text}</div><span class="timestamp">Just now</span>`;
    messages.appendChild(userMsg);

    // Simulate AI response
    const aiMsg = document.createElement('div');
    aiMsg.classList.add('message', 'ai');
    aiMsg.innerHTML = `<div class="message-content">AI: I'm processing your message... 🤖</div><span class="timestamp">Just now</span>`;
    messages.appendChild(aiMsg);

    input.value = '';
    messages.scrollTop = messages.scrollHeight;
  });
</script>
