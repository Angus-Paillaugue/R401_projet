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
    body: ['GET', 'HEAD'].includes(method) ? null : JSON.stringify(body)
  });
  const data = await req.json();
  if (!req.ok) {
    throw new Error(data.status_message);
  }
  return data;
}
