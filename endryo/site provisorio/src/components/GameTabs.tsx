import React, { useState } from 'react';
import { Game } from '../types';
import { games } from '../data/games';
import GameCard from './GameCard';

const GameTabs: React.FC = () => {
  const [activeTab, setActiveTab] = useState('all');
  
  const genres = ['all', ...new Set(games.map(game => 
    game.genre.split(', ')[0].toLowerCase()
  ))];

  const filteredGames = activeTab === 'all' 
    ? games 
    : games.filter(game => 
        game.genre.toLowerCase().includes(activeTab.toLowerCase())
      );

  return (
    <div className="bg-purple-800 rounded-xl p-4 shadow-lg">
      <div className="mb-6 overflow-x-auto scrollbar-hide">
        <div className="flex space-x-2 min-w-max">
          {genres.map(genre => (
            <button
              key={genre}
              onClick={() => setActiveTab(genre)}
              className={`px-4 py-2 rounded-lg transition-all duration-300 capitalize ${
                activeTab === genre
                  ? 'bg-purple-600 text-white font-medium'
                  : 'bg-purple-700/50 text-purple-200 hover:bg-purple-700'
              }`}
            >
              {genre}
            </button>
          ))}
        </div>
      </div>
      
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {filteredGames.map(game => (
          <GameCard key={game.id} game={game} />
        ))}
      </div>
    </div>
  );
};

export default GameTabs;