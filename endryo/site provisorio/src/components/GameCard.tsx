import React, { useState } from 'react';
import { Game } from '../types';

interface GameCardProps {
  game: Game;
}

const GameCard: React.FC<GameCardProps> = ({ game }) => {
  const [activeTab, setActiveTab] = useState('description');
  const [showDetails, setShowDetails] = useState(false);

  const handleTabClick = (tab: string) => {
    setActiveTab(tab);
  };

  return (
    <div 
      className={`bg-purple-900 rounded-lg overflow-hidden shadow-lg transition-all duration-300 ${
        showDetails ? 'transform scale-[1.02]' : ''
      }`}
    >
      <div className="relative h-40 overflow-hidden">
        <img 
          src={game.coverImage} 
          alt={game.title} 
          className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-purple-900 to-transparent"></div>
        <h3 className="absolute bottom-2 left-3 text-white font-bold text-lg">{game.title}</h3>
      </div>
      
      <div className="p-3">
        <div className="flex items-center justify-between mb-3">
          <div className="flex space-x-1">
            <span className="px-2 py-0.5 bg-purple-700 text-purple-200 text-xs rounded-full">
              {game.type}
            </span>
          </div>
          <button 
            onClick={() => setShowDetails(!showDetails)}
            className="text-purple-300 hover:text-white text-sm underline transition-colors"
          >
            {showDetails ? 'Hide Details' : 'Show Details'}
          </button>
        </div>
        
        {showDetails && (
          <div className="mt-2">
            <div className="flex border-b border-purple-700 mb-3">
              <button
                onClick={() => handleTabClick('description')}
                className={`px-3 py-2 text-sm ${
                  activeTab === 'description' 
                    ? 'text-white border-b-2 border-purple-500' 
                    : 'text-purple-300 hover:text-white'
                }`}
              >
                Description
              </button>
              <button
                onClick={() => handleTabClick('details')}
                className={`px-3 py-2 text-sm ${
                  activeTab === 'details' 
                    ? 'text-white border-b-2 border-purple-500' 
                    : 'text-purple-300 hover:text-white'
                }`}
              >
                Details
              </button>
              <button
                onClick={() => handleTabClick('requirements')}
                className={`px-3 py-2 text-sm ${
                  activeTab === 'requirements' 
                    ? 'text-white border-b-2 border-purple-500' 
                    : 'text-purple-300 hover:text-white'
                }`}
              >
                Requirements
              </button>
            </div>
            
            <div className="text-purple-100 text-sm">
              {activeTab === 'description' && (
                <p>{game.description}</p>
              )}
              
              {activeTab === 'details' && (
                <div className="space-y-2">
                  <div>
                    <span className="text-purple-300">Type: </span>
                    <span>{game.type}</span>
                  </div>
                  <div>
                    <span className="text-purple-300">Genre: </span>
                    <span>{game.genre}</span>
                  </div>
                </div>
              )}
              
              {activeTab === 'requirements' && (
                <div className="space-y-2">
                  <div>
                    <span className="text-purple-300">OS: </span>
                    <span>{game.systemRequirements.os}</span>
                  </div>
                  <div>
                    <span className="text-purple-300">Processor: </span>
                    <span>{game.systemRequirements.processor}</span>
                  </div>
                  <div>
                    <span className="text-purple-300">Memory: </span>
                    <span>{game.systemRequirements.memory}</span>
                  </div>
                  <div>
                    <span className="text-purple-300">Graphics: </span>
                    <span>{game.systemRequirements.graphics}</span>
                  </div>
                  <div>
                    <span className="text-purple-300">Storage: </span>
                    <span>{game.systemRequirements.storage}</span>
                  </div>
                </div>
              )}
            </div>
          </div>
        )}
        
        {!showDetails && (
          <p className="text-purple-100 text-sm line-clamp-2">{game.description}</p>
        )}
        
        <div className="mt-3 flex justify-between items-center">
          <span className="text-xs text-purple-300">{game.genre}</span>
          <button className="px-3 py-1 bg-purple-600 hover:bg-purple-500 text-white text-sm rounded transition-colors">
            View Game
          </button>
        </div>
      </div>
    </div>
  );
};

export default GameCard;