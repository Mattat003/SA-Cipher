import { Game } from '../types';

export const games: Game[] = [
  {
    id: '1',
    title: 'Stellar Odyssey',
    description: 'Embark on an epic journey through the cosmos, discovering new worlds and ancient civilizations. Build alliances or wage war across the galaxy in this immersive space adventure.',
    type: 'Single-player / Multiplayer',
    genre: 'Space Exploration, RPG',
    systemRequirements: {
      os: 'Windows 10 64-bit',
      processor: 'Intel Core i5-6600K / AMD Ryzen 5 1600',
      memory: '16 GB RAM',
      graphics: 'NVIDIA GTX 1060 6GB / AMD RX 580 8GB',
      storage: '75 GB available space'
    },
    coverImage: 'https://images.pexels.com/photos/1341279/pexels-photo-1341279.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    isFeatured: true
  },
  {
    id: '2',
    title: 'Shadow Realm',
    description: 'Dive into the darkness and uncover ancient mysteries in this atmospheric action-adventure game. Master arcane abilities and face terrifying creatures from beyond the veil.',
    type: 'Single-player',
    genre: 'Action, Adventure, Horror',
    systemRequirements: {
      os: 'Windows 10 64-bit',
      processor: 'Intel Core i7-4790 / AMD Ryzen 5 2600X',
      memory: '12 GB RAM',
      graphics: 'NVIDIA GTX 1660 / AMD RX 5600 XT',
      storage: '50 GB available space'
    },
    coverImage: 'https://images.pexels.com/photos/3165335/pexels-photo-3165335.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    isFeatured: true
  },
  {
    id: '3',
    title: 'Velocity Rush',
    description: 'Experience the ultimate racing thrill with next-generation physics and stunning visuals. Customize your vehicles and compete against the best racers across detailed tracks worldwide.',
    type: 'Single-player / Multiplayer',
    genre: 'Racing, Sports',
    systemRequirements: {
      os: 'Windows 10 64-bit',
      processor: 'Intel Core i5-8600K / AMD Ryzen 5 3600',
      memory: '8 GB RAM',
      graphics: 'NVIDIA GTX 1650 / AMD RX 5500 XT',
      storage: '60 GB available space'
    },
    coverImage: 'https://images.pexels.com/photos/3807457/pexels-photo-3807457.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    isFeatured: false
  },
  {
    id: '4',
    title: 'Legends of Valor',
    description: 'Forge your destiny in a vast fantasy world filled with magic, monsters, and political intrigue. Build your character, make allies, and shape the fate of kingdoms in this expansive RPG.',
    type: 'Single-player / Co-op',
    genre: 'RPG, Fantasy, Open World',
    systemRequirements: {
      os: 'Windows 10 64-bit',
      processor: 'Intel Core i7-8700K / AMD Ryzen 7 3700X',
      memory: '16 GB RAM',
      graphics: 'NVIDIA RTX 2070 / AMD RX 5700 XT',
      storage: '100 GB available space'
    },
    coverImage: 'https://images.pexels.com/photos/7919/pexels-photo.jpg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    isFeatured: true
  },
  {
    id: '5',
    title: 'Cyber Revolution',
    description: 'Navigate the neon-lit streets of a dystopian future where corporations rule and technology has transformed humanity. Hack, shoot, and negotiate your way through a complex web of intrigue.',
    type: 'Single-player',
    genre: 'Cyberpunk, Action RPG',
    systemRequirements: {
      os: 'Windows 10 64-bit',
      processor: 'Intel Core i7-9700K / AMD Ryzen 7 3800X',
      memory: '16 GB RAM',
      graphics: 'NVIDIA RTX 2080 / AMD RX 6700 XT',
      storage: '80 GB available space'
    },
    coverImage: 'https://images.pexels.com/photos/2531709/pexels-photo-2531709.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    isFeatured: true
  },
  {
    id: '6',
    title: 'Tactical Operations',
    description: 'Lead your elite squad through high-stakes missions around the globe. Plan your approach, coordinate tactics, and execute flawless operations in this strategic team-based shooter.',
    type: 'Single-player / Multiplayer',
    genre: 'Tactical Shooter, Strategy',
    systemRequirements: {
      os: 'Windows 10 64-bit',
      processor: 'Intel Core i5-10600K / AMD Ryzen 5 5600X',
      memory: '12 GB RAM',
      graphics: 'NVIDIA GTX 1660 Ti / AMD RX 5600 XT',
      storage: '50 GB available space'
    },
    coverImage: 'https://images.pexels.com/photos/163036/mario-luigi-yoschi-figures-163036.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1',
    isFeatured: false
  }
];

export const featuredGames = games.filter(game => game.isFeatured);