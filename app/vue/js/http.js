function getJWT() {
  const cookies = document.cookie.split('; ');
  for (const cookie of cookies) {
    if (cookie.startsWith('token=')) {
      return cookie.split('=')[1];
    }
  }

  return null;
}

export async function httpRequest(method = 'GET', url, body = {}) {
  const token = getJWT();
  const headers = {
    'Content-Type': 'application/json'
  };
  if (token) {
    headers['Authorization'] = `Bearer ${token}`;
  }
  const req = await fetch(url, {
    method,
    headers,
    credentials: 'include',
    body: ['GET', 'HEAD'].includes(method) ? null : JSON.stringify(body)
  });
  const data = await req.json();
  if (!req.ok) {
    // If the token is invalid, redirect to the login page
    if (data.status_code === 401) {
      window.location.href = '/vue/log-in.php';
    }
    throw new Error(data.status_message);
  }
  return data;
}
