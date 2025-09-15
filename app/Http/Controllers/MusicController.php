<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Str;

class MusicController extends Controller
{
    public function index()
    {
        // TODO: migrate to Markdown/DB in 6D. Seed with 3 songs here.
        $songs = [
            [
                'slug' => 'axolotl-waddle',
                'title' => 'The Axolotl Waddle',
                'description' => 'A playful movement song about our favorite axolotls—great for classrooms and story time.',
                'duration' => '1:40',
                'audioSrc' => '/media/music/axolotl-waddle/audio.mp3',
                'cover' => '/media/music/axolotl-waddle/cover.jpg',
                'tags' => ['movement', 'science', 'animals'],
            ],
            [
                'slug' => 'splish-splash-bubble-bash',
                'title' => 'Splish Splash Bubble Bash',
                'description' => 'Bathtime rhythms and counting fun—clean beats for little learners.',
                'duration' => '1:41',
                'audioSrc' => '/media/music/splish-splash-bubble-bash/audio.mp3',
                'cover' => '/media/music/splish-splash-bubble-bash/cover.jpg',
                'tags' => ['counting', 'daily routines'],
            ],
            [
                'slug' => 'time-to-get-dressed',
                'title' => 'Time to Get Dressed',
                'description' => 'Morning routine made easy—call-and-response lyrics to help kids build habits.',
                'duration' => '1:17',
                'audioSrc' => '/media/music/time-to-get-dressed/audio.mp3',
                'cover' => '/media/music/time-to-get-dressed/cover.jpg',
                'tags' => ['routines', 'call-and-response'],
            ],
        ];

        return Inertia::render('Music', compact('songs'));
    }

    public function show(string $slug)
    {
        $library = collect([
            'axolotl-waddle' => [
                'title' => 'The Axolotl Waddle',
                'subtitle' => 'Movement • Science • Animals',
                'description' => 'A playful movement song about our favorite axolotls—great for classrooms and story time!',
                'duration' => '1:40',
                'audioSrc' => '/media/music/axolotl-waddle/audio.mp3',
                'cover' => '/media/music/axolotl-waddle/cover.jpg',
                'key' => 'G Major',
                'bpm' => 116,
                'lyrics' => "Verse 1:\nDeep in the water, where the rivers flow,\nLives a little creature that you might not know!\nA big wide smile and feathery hair,\nA squishy little body that wobbles everywhere!\n\nChorus:\nWiggle to the left, wiggle to the right!\nSwish your tail and hold on tight!\nHop up once, then sink down low,\nThat’s the axolotl waddle—here we go!\n\nVerse 2:\nAxolotls never grow up, oh wow!\nThey stay little babies, even now!\nThey can regrow a leg, a tail, or two,\nLike a real-life magic trick—who knew?\n\nChorus:\nWiggle to the left, wiggle to the right!\nSwish your tail and hold on tight!\nHop up once, then sink down low,\nThat’s the axolotl waddle—here we go!\n\nBridge:\nPink ones, white ones, speckled and black,\nAll of them swimming in a happy pack!\nThey wiggle and giggle all through the day,\nDoing the Axolotl Waddle their own fun way!\n\nFinal Chorus:\nWiggle to the left, wiggle to the right!\nSwish your tail and hold on tight!\nOne last time, let’s waddle and spin,\nAxolotl fun—let’s do it again!",
                'downloads' => [
                    ['label' => 'Lyric Sheet (PDF)', 'href' => '/media/music/axolotl-waddle/lyric-sheet.pdf'],
                    //['label' => 'Coloring Page (PDF)', 'href' => '/media/music/axolotl-waddle/coloring-page.pdf'],
                ],
                'videoEmbed' => null, // or a YouTube/Vimeo embed URL later
                'tags' => ['movement', 'science', 'animals'],
            ],
            'splish-splash-bubble-bash' => [
                'title' => 'Splish Splash Bubble Bash',
                'subtitle' => 'Counting • Daily Routines',
                'description' => 'Bathtime rhythms and counting fun.',
                'duration' => '1:41',
                'audioSrc' => '/media/music/splish-splash-bubble-bash/audio.mp3',
                'cover' => '/media/music/splish-splash-bubble-bash/cover.jpg',
                'key' => 'C Major',
                'bpm' => 104,
                'lyrics' => "Verse 1:\nWoke up muddy from a dragon dream,\nGrass in my hair and jam on my knee!\nMom said, “Hey now, it’s time to clean,”\nLet’s fill the tub—it’s a bathtime scene!\n\nChorus:\nSplish splash, bubble bash,\nRubber ducky's doing a cannon crash!\nWash my arms, wash my nose,\nScrub my fingers and my toes!\nSplish splash, scrub-a-doo,\nI’m the cleanest kid—how 'bout you?\n\nVerse 2:\nShampoo hat on my foamy crown,\nScrub-a-dub-dub till the dirt goes down.\nWater swirls like a magic spell,\nI’m squeaky clean and I smell so well!\n\nChorus:\nSplish splash, bubble bash,\nSoap parade and a sponge mustache!\nRinse my ears, rinse my chin,\nMake sure I get behind the skin!\nSplish splash, scrub-a-dee,\nBeing clean feels great to me!\n\nBridge:\nGerms like hiding where you can’t see,\nSo scrub-a-dub smart, and you’ll be germ-free!\nDon’t forget elbows, knees, and feet,\nBathtime’s how we stay fresh and neat!\n\nFinal Chorus:\nSplish splash, bubble bash,\nBathtime’s over in a splashy flash!\nWrap me up, towel hug,\nTime to chill in my cozy rug.\nSplish splash, I had a blast,\nCan’t wait ‘til the next bath bash!",
                'downloads' => [
                    ['label' => 'Lyric Sheet (PDF)', 'href' => '/media/music/splish-splash-bubble-bash/lyric-sheet.pdf'],
                ],
                'videoEmbed' => null,
                'tags' => ['counting', 'daily routines'],
            ],
            'time-to-get-dressed' => [
                'title' => 'Time to Get Dressed',
                'subtitle' => 'Routines • Call-and-Response',
                'description' => 'Morning routine made easy—call-and-response lyrics to help kids build habits.',
                'duration' => '1:17',
                'audioSrc' => '/media/music/time-to-get-dressed/audio.mp3',
                'cover' => '/media/music/time-to-get-dressed/cover.jpg',
                'key' => 'D Major',
                'bpm' => 98,
                'lyrics' => "Verse 1:\nGood morning, time to rise,\nOpen up those sleepy eyes!\nStretch your arms, wiggle your toes,\nLet’s get dressed—here we go!\n\nChorus:\nShirt on my feet? Oh no, not neat!\nPants on my head? That’s not what Mom said!\nOops, my hat’s on my knee,\nSomebody help me, please!\n\nVerse 2:\nSocks go where? Oh, on my feet!\nShoes go on? Not on my seat!\nJacket goes on—zip it up tight,\nNow we’re ready—looks just right!\n\nChorus:\nGloves on my ears? That’s kinda weird!\nShoes on my hands? I look like a deer!\nOh no, my scarf’s in my sleeve,\nSomebody help me, please!\n\nVerse 3:\nHat on my head, gloves on just right,\nPants on my legs—now I look bright!\nJacket zipped up, shoes nice and neat,\nNow I’m ready—time to move my feet!\n\nFinal Chorus:\nShirt on my tummy, socks on my feet,\nHat on my head—now I’m looking neat!\nNo more mix-ups, hip-hip hooray!\nI’m ready for an awesome day!",
                'downloads' => [
                    ['label' => 'Lyric Sheet (PDF)', 'href' => '/media/music/time-to-get-dressed/lyric-sheet.pdf'],
                ],
                'videoEmbed' => null,
                'tags' => ['routines', 'call-and-response'],
            ],
        ]);

        abort_unless($library->has($slug), 404);

        return Inertia::render('Song', [
            'slug' => $slug,
            'song' => $library[$slug],
            'related' => $library->except($slug)->take(2)->values()->all(),
        ]);
    }
}
