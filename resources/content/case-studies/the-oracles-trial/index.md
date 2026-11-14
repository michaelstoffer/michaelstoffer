---
slug: the-oracles-trial
title: The Oracle's Trial — Building Interacting Game Systems in C++
summary: "A completed Full Sail coursework project: a Greek mythology-inspired console adventure with branching choices, player traits, inventory, and a puzzle across six temple rooms."
problem: As the adventure grew, choices, inventory, puzzles, and room state needed to interact consistently without making the game flow difficult to maintain.
approach: Separated responsibilities across Game, Player, Room, Item, and Puzzle classes, then refined input handling, one-time rewards, room access, and new-game resets over four milestones.
result: A completed C++ console application with six connected rooms, choice-dependent interactions, inventory rewards, and documented final playthrough testing.
cover: /media/software/the-oracles-trial/cover.webp
tags: [C++, Game Development, Object-Oriented Design, Coursework]
featured: false
stack: [C++, Visual Studio, Git, GitHub]
links:
  - href: https://youtu.be/KjAYiiyTRxk
    label: Watch the project demo
---

## Project Context

The Oracle's Trial is a completed educational project from Full Sail University's Project & Portfolio I course. I built it over four milestones as a Greek mythology-inspired text adventure.

The player explores a temple, makes choices, collects items, develops traits, and solves a puzzle. The engineering challenge was to make those systems influence one another while keeping the program understandable and reliable across repeated playthroughs.

## From Rooms to Consequences

The first milestone established the main menu, player creation, inventory, and the opening rooms. Later milestones added player traits, a puzzle, more rooms, and interactions that respond to earlier decisions.

The final temple contains six rooms: the Temple Entrance, Weeping Statue, Sealed Door, Hall of Echoes, Chamber of Athena, and Broken Sanctuary.

The design tracks wisdom, courage, mercy, greed, and deception. Choices can change those values and influence later dialogue and interactions. Collected items also matter: an inventory reward can support an interaction later in the same playthrough.

That made state management part of the experience. An item should be earned once, a completed puzzle should remain completed, and starting a new game should give the player a clean beginning.

## Giving Classes Clear Responsibilities

I organized the program around five interacting classes:

- **Game** coordinates the main loop, room flow, and player interactions.
- **Player** owns the name, traits, and inventory.
- **Room** represents room information and interaction state.
- **Item** represents inventory objects and their descriptions.
- **Puzzle** holds its prompt, choices, answer, and solved state.

The inventory uses `std::vector<Item>` and checks for an existing item before adding it. That keeps duplicate prevention inside the player model instead of making each room implement its own version.

As the game expanded, I broke larger gameplay methods into smaller helpers. During the final milestone, I replaced unexplained numeric room indexes with named room values and a `GetRoom()` helper that returns the existing room by reference. The change made the flow easier to read while preserving the working room objects.

## Handling Repeated and Invalid Actions

The console interface uses numbered choices and explicit input ranges. I reviewed invalid numbers, letters, mixed input, and blank player names so an unexpected response would not leave the player stuck in an invalid state.

I also reviewed repeated actions. Puzzle rewards and altar interactions needed protection against granting the same benefit repeatedly. Viewing player information or inventory needed to return to a usable menu rather than interrupting room flow.

New-game behavior received the same attention. Player traits, inventory, room interactions, and puzzle completion all needed to reset when another playthrough began.

## Final Verification

The final milestone records successful compilation and execution, multiple full playthroughs, and checks of both the Wisdom and Greed paths. It also documents puzzle failures and retries, successful completion, duplicate reward prevention, invalid input, room navigation, and resets between games.

This was manual gameplay verification documented for the course. It supported the final delivery of the console application and provided concrete evidence for the development reflection and demo.

## What This Demonstrates

- Modeling interacting gameplay systems with C++ classes and standard containers.
- Keeping player and puzzle state consistent within a playthrough.
- Improving readability through small, targeted changes to working code.
- Testing repeat interactions and new-game behavior, not only the first successful path.
- Delivering a scoped project through milestones, GitHub issues, branches, and pull requests.

*Interested in the development process? [Watch the demo](https://youtu.be/KjAYiiyTRxk) or [get in touch.](/contact)*
