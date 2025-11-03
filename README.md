# Audio Archive

A personal audio library where you can upload, organize, and play your own music — with playlists, equalizer presets, and a beautiful listening experience.

## Features

Upload Your Audio: Easily upload your music or podcast files to your personal library (stored securely on S3).

Organize by Artist & Album: Automatically categorize your uploads by artist and album for quick browsing.

Playlists: Create and manage playlists from your uploaded tracks.

Smart Playback Controls: Play, pause, skip, shuffle, and repeat - all without interruptions across pages.

## Installation

After cloning this repo, create a local MySQL database with the name `audio_archive`, and connect to it.

Then, run the following commands from your project root:

```
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan flux:activate
npm install
npm run dev
```

Now, open the project in your browser (Typically http://audio-archive.test) and use the following credentials to log in:

```
Email: admin@example.com
Password: password
```

## Testing

Run tests with:

`php artisan test`
