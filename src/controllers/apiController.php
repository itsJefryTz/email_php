<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

function helloWorld() {
  return new JsonResponse([
    'message' => 'Hello, API!',
    'status' => 'success'
  ]);
}

function sendEmail(Request $request) {
  $method = $request->getMethod();

  if ($method !== 'POST') {
    return new JsonResponse([
      'message' => 'Method not allowed. Use POST.',
      'status' => 'error'
    ], Response::HTTP_METHOD_NOT_ALLOWED);
  }

  try {
    $data = $request->toArray();
    $s = $data['settings'] ?? null;
    $settingsData = (!empty($s['host']) && !empty($s['port']) && !empty($s['user']) && !empty($s['pass']) && !empty($s['encryption'])) ? $s : null;
    $e = $data['email'] ?? null;
    $emailData = (!empty($e['to']) && !empty($e['subject']) && !empty($e['body'])) ? $e : null;
  } catch (\Exception $e) {
    return new JsonResponse([
      'message' => 'Invalid JSON or empty body',
      'status' => 'error'
    ], Response::HTTP_BAD_REQUEST);
  }

  if (!$settingsData || !$emailData) {
    return new JsonResponse([
      'message' => 'Missing settings or email data',
      'status' => 'error'
    ], Response::HTTP_BAD_REQUEST);
  }

  if (!filter_var($emailData['to'], FILTER_VALIDATE_EMAIL)) {
    return new JsonResponse([
      'status' => 'error',
      'message' => 'The email format is invalid.'
    ], Response::HTTP_BAD_REQUEST);
  }

  // if everything is fine.
  $dsn = sprintf(
    "%s://%s:%s@%s:%s?verify_peer=0",
    $settingsData['encryption'] ?? 'smtps',
    urlencode($settingsData['user']),
    urlencode($settingsData['pass']),
    $settingsData['host'],
    $settingsData['port'] ?? 465
  );

  try {
    $transport = Transport::fromDsn($dsn);
    $mailer = new Mailer($transport);

    $email = (new Email())
      ->from($settingsData['user'])
      ->to($emailData['to'])
      ->subject($emailData['subject'])
      ->html($emailData['body']);

    $mailer->send($email);
    return new JsonResponse([
      'message' => 'Email sent successfully!',
      'status' => 'success'
    ], Response::HTTP_OK);
  } catch (\Exception $e) {
    return new JsonResponse([
      'status' => 'error',
      'message' => $e->getMessage()
    ], Response::HTTP_INTERNAL_SERVER_ERROR);
  }
}
?>