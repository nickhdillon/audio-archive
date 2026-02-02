<?php

declare(strict_types=1);

namespace App\Enums;

enum BibleBook: string
{
    case GENESIS = 'Genesis';
    case EXODUS = 'Exodus';
    case LEVITICUS = 'Leviticus';
    case NUMBERS = 'Numbers';
    case DEUTERONOMY = 'Deuteronomy';
    case JOSHUA = 'Joshua';
    case JUDGES = 'Judges';
    case RUTH = 'Ruth';
    case FIRST_SAMUEL = '1 Samuel';
    case SECOND_SAMUEL = '2 Samuel';
    case FIRST_KINGS = '1 Kings';
    case SECOND_KINGS = '2 Kings';
    case FIRST_CHRONICLES = '1 Chronicles';
    case SECOND_CHRONICLES = '2 Chronicles';
    case EZRA = 'Ezra';
    case NEHEMIAH = 'Nehemiah';
    case ESTHER = 'Esther';
    case JOB = 'Job';
    case PSALMS = 'Psalms';
    case PROVERBS = 'Proverbs';
    case ECCLESIASTES = 'Ecclesiastes';
    case SONG_OF_SOLOMON = 'Song Of Solomon';
    case ISAIAH = 'Isaiah';
    case JEREMIAH = 'Jeremiah';
    case LAMENTATIONS = 'Lamentations';
    case EZEKIEL = 'Ezekiel';
    case DANIEL = 'Daniel';
    case HOSEA = 'Hosea';
    case JOEL = 'Joel';
    case AMOS = 'Amos';
    case OBADIAH = 'Obadiah';
    case JONAH = 'Jonah';
    case MICAH = 'Micah';
    case NAHUM = 'Nahum';
    case HABAKKUK = 'Habakkuk';
    case ZEPHANIAH = 'Zephaniah';
    case HAGGAI = 'Haggai';
    case ZECHARIAH = 'Zechariah';
    case MALACHI = 'Malachi';

    case MATTHEW = 'Matthew';
    case MARK = 'Mark';
    case LUKE = 'Luke';
    case JOHN = 'John';
    case ACTS = 'Acts';
    case ROMANS = 'Romans';
    case FIRST_CORINTHIANS = '1 Corinthians';
    case SECOND_CORINTHIANS = '2 Corinthians';
    case GALATIANS = 'Galatians';
    case EPHESIANS = 'Ephesians';
    case PHILIPPIANS = 'Philippians';
    case COLOSSIANS = 'Colossians';
    case FIRST_THESSALONIANS = '1 Thessalonians';
    case SECOND_THESSALONIANS = '2 Thessalonians';
    case FIRST_TIMOTHY = '1 Timothy';
    case SECOND_TIMOTHY = '2 Timothy';
    case TITUS = 'Titus';
    case PHILEMON = 'Philemon';
    case HEBREWS = 'Hebrews';
    case JAMES = 'James';
    case FIRST_PETER = '1 Peter';
    case SECOND_PETER = '2 Peter';
    case FIRST_JOHN = '1 John';
    case SECOND_JOHN = '2 John';
    case THIRD_JOHN = '3 John';
    case JUDE = 'Jude';
    case REVELATION = 'Revelation';

    public function order(): int
    {
        return match ($this) {
            self::GENESIS => 1,
            self::EXODUS => 2,
            self::LEVITICUS => 3,
            self::NUMBERS => 4,
            self::DEUTERONOMY => 5,
            self::JOSHUA => 6,
            self::JUDGES => 7,
            self::RUTH => 8,
            self::FIRST_SAMUEL => 9,
            self::SECOND_SAMUEL => 10,
            self::FIRST_KINGS => 11,
            self::SECOND_KINGS => 12,
            self::FIRST_CHRONICLES => 13,
            self::SECOND_CHRONICLES => 14,
            self::EZRA => 15,
            self::NEHEMIAH => 16,
            self::ESTHER => 17,
            self::JOB => 18,
            self::PSALMS => 19,
            self::PROVERBS => 20,
            self::ECCLESIASTES => 21,
            self::SONG_OF_SOLOMON => 22,
            self::ISAIAH => 23,
            self::JEREMIAH => 24,
            self::LAMENTATIONS => 25,
            self::EZEKIEL => 26,
            self::DANIEL => 27,
            self::HOSEA => 28,
            self::JOEL => 29,
            self::AMOS => 30,
            self::OBADIAH => 31,
            self::JONAH => 32,
            self::MICAH => 33,
            self::NAHUM => 34,
            self::HABAKKUK => 35,
            self::ZEPHANIAH => 36,
            self::HAGGAI => 37,
            self::ZECHARIAH => 38,
            self::MALACHI => 39,

            self::MATTHEW => 1,
            self::MARK => 2,
            self::LUKE => 3,
            self::JOHN => 4,
            self::ACTS => 5,
            self::ROMANS => 6,
            self::FIRST_CORINTHIANS => 7,
            self::SECOND_CORINTHIANS => 8,
            self::GALATIANS => 9,
            self::EPHESIANS => 10,
            self::PHILIPPIANS => 11,
            self::COLOSSIANS => 12,
            self::FIRST_THESSALONIANS => 13,
            self::SECOND_THESSALONIANS => 14,
            self::FIRST_TIMOTHY => 15,
            self::SECOND_TIMOTHY => 16,
            self::TITUS => 17,
            self::PHILEMON => 18,
            self::HEBREWS => 19,
            self::JAMES => 20,
            self::FIRST_PETER => 21,
            self::SECOND_PETER => 22,
            self::FIRST_JOHN => 23,
            self::SECOND_JOHN => 24,
            self::THIRD_JOHN => 25,
            self::JUDE => 26,
            self::REVELATION => 27
        };
    }
}
