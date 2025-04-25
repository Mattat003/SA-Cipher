import React from 'react';
import Navbar from './components/Navbar';
import GameCarousel from './components/GameCarousel';
import GameTabs from './components/GameTabs';
import Footer from './components/Footer';

function App() {
  return (
    <div className="bg-purple-950 text-white min-h-screen">
      <Navbar />
      
      <main className="container mx-auto px-4 pt-20 pb-8">
        <div className="mt-6 mb-8">
          <GameCarousel />
        </div>
        
        <div className="mb-8">
          <h2 className="text-2xl font-bold mb-4">Game Library</h2>
          <GameTabs />
        </div>
      </main>
      
      <Footer />
    </div>
  );
}

export default App;