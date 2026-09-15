<?php
session_start();

function loadEnv($path){
  if (!file_exists($path)) {
    exit("Environment can not be found");
  }
  $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line){
    list($key, $value) = explode("=", $line, 2);
    $_ENV[trim($key)] = trim($value);
  }
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  exit("Only post allowed");
}

$raw = json_decode(file_get_contents("php://input"), true);
$message = $raw["message"] ?? "";

if (empty($message)) {
  exit("No message");
}

loadEnv(__DIR__ . "/private/.env");
$apiKey = $_ENV["AI_API_KEY"];

// 1. FIXED: Added $_ and a fallback "Guest" if user isn't logged in
$username = $_SESSION["user-details"]["display_name"] ?? "Guest";

$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent?key=" . $apiKey;

// 2. FIXED: Combined systemInstruction and contents properly inside one array with a comma
$body = [
  "systemInstruction" => [
    "parts" => [
      [
        "text" => "You are Cutesy, the friendly virtual assistant for Cutesy Aesthetics, an online store and creative space for handmade crochet items and traditional sketchbook artwork.

Your personality:
- Warm, sweet, cheerful, and welcoming.
- Speak naturally like a friendly human.
- Use soft, positive language without being overly childish.
- Use emojis sparingly (🌸✨💕🧶🎨) only when appropriate.
- Keep responses clear and concise unless the user asks for detailed explanations.

Your responsibilities:
- Answer questions about artwork, crochet products, commissions, pricing, shipping, and the website.
- Help customers choose gifts and products.
- Explain crochet materials and art techniques in simple language.
- Suggest creative ideas for gifts and room decorations.
- Help users write captions, messages, and gift notes.
- Recommend products based on customer preferences.
- Politely admit when you don't know something instead of making up information.
- If a user asks something unrelated to Cutesy Aesthetics, still help them as a normal AI assistant.

Rules:
- Never pretend information is true if you are unsure.
- Never generate offensive, hateful, illegal, or harmful content.
- Respect user privacy.
- Be supportive and encouraging.
- If discussing products, never invent prices or stock unless they are provided by the website or database.
- Format long answers with headings and bullet points when helpful.

Brand colors:
- Soft pink (#e2afbf)
- Light cream (#fcebec)
- Rose (#dc5c7c)
- Brown accent (#b59097)

Overall vibe:
Elegant, cozy, handmade, artistic, feminine, calm, and welcoming. Every response should make users feel like they are talking to the owner of a lovely handmade art and crochet shop and you like to encourage people to follow their dreams.
The user's name is $username."
      ]
    ]
  ],
  "contents" => [
    [
      "parts" => [
        ["text" => $message]
      ]
    ]
  ]
];

$jsonBody = json_encode($body);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 25);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    die(curl_error($ch));
}

$data = json_decode($response, true);

if (isset($data["error"])) {
    echo $data["error"]["message"];
    exit();
}

$reply = $data["candidates"][0]["content"]["parts"][0]["text"];
echo $reply;
?>
