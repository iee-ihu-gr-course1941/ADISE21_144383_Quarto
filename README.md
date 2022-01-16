# ADISE21_144383_Quarto

Table of Contents
=================
   
   * [Απαιτήσεις](#απαιτήσεις)
   * [Οδηγίες Εγκατάστασης](#οδηγίες-εγκατάστασης)
   * [Περιγραφή API](#περιγραφή-api)
      * [Methods](#methods)
         * [Board](#board)
            * [Ανάγνωση Board](#ανάγνωση-board)
            * [Αρχικοποίηση Board](#αρχικοποίηση-board)
         * [Piece](#piece)
            * [Ανάγνωση Θέσης/Πιονιού](#ανάγνωση-θέσηςπιονιού)
            * [Μεταβολή Θέσης Πιονιού](#μεταβολή-θέσης-πιονιού)
         * [Player](#player)
            * [Ανάγνωση στοιχείων παίκτη](#ανάγνωση-στοιχείων-παίκτη)
            * [Καθορισμός στοιχείων παίκτη](#καθορισμός-στοιχείων-παίκτη)
         * [Status](#status)
            * [Ανάγνωση κατάστασης παιχνιδιού](#ανάγνωση-κατάστασης-παιχνιδιού)
      * [Entities](#entities)
         * [Board](#board-1)
         * [Players](#players)
         * [Game_status](#game_status)


# Demo Page

Μπορείτε να κατεβάσετε τοπικά ή να επισκευτείτε την σελίδα: 
https://users.it.teithe.gr/~it144383/adise21/ADISE21_144383_Quarto/www/

## Απαιτήσεις

* Apache2
* Mysql Server
* php

## Οδηγίες Εγκατάστασης

 * Κάντε clone το project σε κάποιον φάκελο <br/>
  `$ git clone https://github.com/iee-ihu-gr-course1941/ADISE21_144383_Quarto.git`

 * Βεβαιωθείτε ότι ο φάκελος είναι προσβάσιμος από τον Apache Server. πιθανόν να χρειαστεί να καθορίσετε τις παρακάτω ρυθμίσεις.

 * Θα πρέπει να δημιουργήσετε στην Mysql την βάση με όνομα 'quartodb' και να φορτώσετε σε αυτήν την βάση τα δεδομένα από το αρχείο quartodb creation.sql

 * Θα πρέπει να φτιάξετε το αρχείο lib/dbpass.php το οποίο να περιέχει:
```
    <?php
	$DB_PASS = 'κωδικός';
	$DB_USER = 'όνομα χρήστη';
    ?>
```

# Περιγραφή Παιχνιδιού

Το Κουάρτο (Quarto) παίζεται ως εξής: 

	1. Ο πρώτος παίκτης διαλέγει ένα πιόνι για τον δεύτερο παίκτη
	2. Ο δεύτερος παίκτης τοποθετεί το πιόνι σε μία άδεια θέση του ταμπλό και διαλέγει ένα πιόνι για τον πρώτο παίκτη.
	3. Ο πρώτος παίκτης τοποθετεί το πιόνι σε μία άδεια θέση του ταμπλό και διαλέγει ένα πιόνι για τον δεύτερο παίκτη.
	4. Οι κανόνες 2 και 3 επαναλαμβάνονται μέχρι ένας από τους δύο παίκτες να συμπληρώσει 4 πιόνια με ένα κοινό χαρακτηριστικό σε μία σειρά (οριζόντια, κάθετα ή διαγώνια) ή μέχρι να τοποθετηθούν όλα τα πιόνια στο ταμπλό.
	
	Αν έχουν τοποθετηθεί όλα τα πιόνια στο ταμπλό χωρίς να έχει συμπληρωθεί τετράδα, τότε το παιχνίδι λήγει με ισοπαλία. 


Η εφαρμογή απαπτύχθηκε μέχρι το σημείο .....(αναφέρετε τι υλοποιήσατε και τι όχι)

## Συντελεστές

Χρήστος Χατζηγεωργίου: PHP API

Χρήστος Χατζηγεωργίου: Σχεδιασμός mysql


# Περιγραφή API

## Methods


### Board
#### Ανάγνωση Board

```
GET /board/
```

Επιστρέφει το [Board](#Board).

#### Αρχικοποίηση Board
```
POST /board/
```

Αρχικοποιεί το Board, δηλαδή το παιχνίδι. Γίνονται reset τα πάντα σε σχέση με το παιχνίδι.
Επιστρέφει το [Board](#Board).

### Piece
#### Ανάγνωση Θέσης/Πιονιού

```
GET /board/piece/:x/:y/
```

Κάνει την κίνηση του πιονιού από την θέση x,y στην νέα θέση. Προφανώς ελέγχεται η κίνηση αν είναι νόμιμη καθώς και αν είναι η σειρά του παίκτη να παίξει με βάση το token.
Επιστρέφει τα στοιχεία από το [Board](#Board-1) με συντεταγμένες x,y.
Περιλαμβάνει το χρώμα του πιονιού και τον τύπο.

#### Μεταβολή Θέσης Πιονιού

```
PUT /board/piece/:x/:y/:piece_id
```
Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `x`               | Η νέα θέση x                | yes        |
| `y`               | Η νέα θέση y                | yes        |
| `piece_id`        | Το id του πιονιού που επιλέγεται για τον αντίπαλο   | yes        |


Επιστρέφει τα στοιχεία από το [Board](#Board-1) με συντεταγμένες x,y.
Περιλαμβάνει το χρώμα του πιονιού και τον τύπο


### Player

#### Ανάγνωση στοιχείων παίκτη
```
GET /players/:p
```

Επιστρέφει τα στοιχεία του παίκτη p ή όλων των παικτών αν παραληφθεί. Το p μπορεί να είναι '1' ή '2'.


#### Καθορισμός στοιχείων παίκτη
```
PUT /players/:playerNumber/:username
```
Json Data:

| Field             | Description                 | Required   |
| ----------------- | --------------------------- | ---------- |
| `playerNumber`    | Ο αριθμός του παίκτη p.     | yes        |
| `username`        | To username του παίκτη p   | yes        |


Επιστρέφει τα στοιχεία του παίκτη και ένα token. Το token πρέπει να το χρησιμοποιεί ο παίκτης καθόλη τη διάρκεια του παιχνιδιού.

### Status

#### Ανάγνωση κατάστασης παιχνιδιού
```
GET /status/
```

Επιστρέφει το στοιχείο [Game_status](#Game_status).



## Entities


### Board
---------

Το board είναι ένας πίνακας, ο οποίος στο κάθε στοιχείο έχει τα παρακάτω:


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `x`                      | H συντεταγμένη x του τετραγώνου              | 1..4                                |
| `y`                      | H συντεταγμένη y του τετραγώνου              | 1..4                                |
| `piece_id`               | Το id του πιονιού                            | 1..16                               |

### Players
---------

O κάθε παίκτης έχει τα παρακάτω στοιχεία:


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `username`               | Όνομα παίκτη                                 | String                              |
| `playerNumber`           | Ο αριθμός του κάθε παίκτη                    | '1','2'                             |
| `token  `                | To κρυφό token του παίκτη. Επιστρέφεται μόνο τη στιγμή της εισόδου του παίκτη στο παιχνίδι | HEX |


### Game_status
---------

H κατάσταση παιχνιδιού έχει τα παρακάτω στοιχεία:


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `status  `               | Κατάσταση             | 'not active', 'initialized', 'started', 'ended', 'aborded'     |
| `p_turn`                 | Ο αριθμός του παίκτη που παίζει        | '1','2',null                              |
| `result`                 | Ο αριθμός του παίκτη που κέρδισε |'1','2',null                              |
| `last_change`            | Τελευταία αλλαγή/ενέργεια στην κατάσταση του παιχνιδιού         | timestamp |


### Pieces
---------

To κάθε πιόνι έχει τα παρακάτω στοιχεία:


| Attribute                | Description                                  | Values                              |
| ------------------------ | -------------------------------------------- | ----------------------------------- |
| `id`                     | Id πιονιού                                   | 1..16                               |
| `White`                  | Ιδιότητα του πιονιού                         | Boolean                             |
| `Square  `               | Iδιότητα του πιονιού                        | Boolean                             |
| `Tall`                   | Ιδιότητα του πιονιού                         | Boolean                             |
| `Hollow`                 | Ο αριθμός του κάθε παίκτη                    | Boolean                             |
| `Player  `               | Ο αριθμός του παίκτη στον οποίο έχει ανατεθεί το πιόνι  | '1','2',null             |


### Empty_board
---------

Διατηρεί την αρχική κατάσταση του πίνακα Board.


### Pieces_all
---------

Διατηρεί την αρχική κατάσταση του πίνακα Pieces.
