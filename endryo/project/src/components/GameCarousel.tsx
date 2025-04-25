import React, { useState, useEffect } from 'react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { Game } from '../types';
import { featuredGames } from '../data/games';

const GameCarousel: React.FC = () => {
  const [currentIndex, setCurrentIndex] = useState(0);
  const [isTransitioning, setIsTransitioning] = useState(false);

  const goToNext = () => {
    if (isTransitioning) return;
    
    setIsTransitioning(true);
    setCurrentIndex((prevIndex) => (prevIndex + 1) % featuredGames.length);
  };

  const goToPrev = () => {
    if (isTransitioning) return;
    
    setIsTransitioning(true);
    setCurrentIndex((prevIndex) => 
      prevIndex === 0 ? featuredGames.length - 1 : prevIndex - 1
    );
  };

  const handleTransitionEnd = () => {
    setIsTransitioning(false);
  };

  useEffect(() => {
    const interval = setInterval(goToNext, 5000);
    return () => clearInterval(interval);
  }, []);

  return (
    <div className="relative w-full overflow-hidden h-[350px] md:h-[400px] rounded-xl shadow-lg">
      <div className="absolute inset-0 bg-gradient-to-t from-purple-900/80 via-purple-900/40 to-transparent z-10" />
      
      {/* Carousel Images */}
      <div 
        className="flex transition-transform duration-500 ease-in-out h-full"
        style={{ transform: `translateX(-${currentIndex * 100}%)` }}
        onTransitionEnd={handleTransitionEnd}
      >
        {featuredGames.map((game) => (
          <div 
            key={game.id} 
            className="min-w-full h-full relative overflow-hidden"
          >
            <img 
              src={game.coverImage} 
              alt={game.title} 
              className="object-cover w-full h-full"
            />
            <div className="absolute bottom-0 left-0 w-full p-6 z-20">
              <h2 className="text-3xl font-bold text-white mb-2">{game.title}</h2>
              <p className="text-purple-100 line-clamp-2 mb-4 max-w-2xl">{game.description}</p>
              <button className="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors duration-300">
                View Game
              </button>
            </div>
          </div>
        ))}
      </div>
      
      {/* Navigation Buttons */}
      <button 
        onClick={goToPrev}
        className="absolute top-1/2 left-4 -translate-y-1/2 bg-purple-800/70 hover:bg-purple-700 text-white p-2 rounded-full z-20 transition-colors"
      >
        <ChevronLeft size={24} />
      </button>
      
      <button 
        onClick={goToNext}
        className="absolute top-1/2 right-4 -translate-y-1/2 bg-purple-800/70 hover:bg-purple-700 text-white p-2 rounded-full z-20 transition-colors"
      >
        <ChevronRight size={24} />
      </button>
      
      {/* Indicators */}
      <div className="absolute bottom-4 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
        {featuredGames.map((_, index) => (
          <button
            key={index}
            onClick={() => {
              if (!isTransitioning) {
                setIsTransitioning(true);
                setCurrentIndex(index);
              }
            }}
            className={`w-2 h-2 rounded-full transition-all ${
              index === currentIndex 
                ? 'bg-white w-6' 
                : 'bg-white/50 hover:bg-white/70'
            }`}
          />
        ))}
      </div>
    </div>
  );
};

export default GameCarousel;