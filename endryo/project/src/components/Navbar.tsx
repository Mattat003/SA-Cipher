import React, { useState } from 'react';
import { Menu, X, Plus, Users, TowerControl as GameController, Search } from 'lucide-react';
import UserProfile from './UserProfile';
import { currentUser } from '../data/user';

const Navbar: React.FC = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isProfileOpen, setIsProfileOpen] = useState(false);

  return (
    <header className="fixed top-0 left-0 right-0 bg-purple-900 text-white z-50 shadow-lg">
      <div className="container mx-auto px-4">
        <div className="flex items-center justify-between h-16">
          {/* Logo */}
          <div className="flex items-center space-x-2">
            <GameController size={24} className="text-purple-300" />
            <span className="font-bold text-xl">GameVault</span>
          </div>

          {/* Search */}
          <div className="hidden md:flex items-center bg-purple-800 rounded-full px-3 py-1 flex-1 max-w-md mx-4">
            <Search size={18} className="text-purple-300 mr-2" />
            <input 
              type="text" 
              placeholder="Search games..." 
              className="bg-transparent border-none outline-none w-full text-white" 
            />
          </div>

          {/* Desktop Navigation */}
          <nav className="hidden md:flex items-center space-x-6">
            <button className="hover:text-purple-300 transition duration-200 flex items-center">
              <GameController size={18} className="mr-1" />
              <span>Connect</span>
            </button>
            <button className="hover:text-purple-300 transition duration-200 flex items-center">
              <Plus size={18} className="mr-1" />
              <span>Create</span>
            </button>
            <button className="hover:text-purple-300 transition duration-200 flex items-center">
              <Plus size={18} className="mr-1" />
              <span>Add</span>
            </button>
            <button className="hover:text-purple-300 transition duration-200 flex items-center">
              <Users size={18} className="mr-1" />
              <span>Friends</span>
            </button>
            <button 
              onClick={() => setIsProfileOpen(!isProfileOpen)}
              className="flex items-center space-x-2 hover:opacity-80 transition"
            >
              <img 
                src={currentUser.avatar} 
                alt="Profile" 
                className="w-8 h-8 rounded-full object-cover border-2 border-purple-400" 
              />
            </button>
          </nav>

          {/* Mobile menu button */}
          <button 
            onClick={() => setIsMenuOpen(!isMenuOpen)} 
            className="md:hidden text-white"
          >
            {isMenuOpen ? <X size={24} /> : <Menu size={24} />}
          </button>
        </div>
      </div>

      {/* Mobile menu */}
      {isMenuOpen && (
        <div className="md:hidden bg-purple-800 pb-4">
          <div className="px-4 pt-2 pb-3 space-y-1">
            <div className="flex items-center bg-purple-700 rounded-lg px-3 py-2 mb-4">
              <Search size={18} className="text-purple-300 mr-2" />
              <input 
                type="text" 
                placeholder="Search games..." 
                className="bg-transparent border-none outline-none w-full text-white" 
              />
            </div>
            
            <button className="block w-full text-left px-3 py-2 rounded-md hover:bg-purple-700 transition duration-200 flex items-center">
              <GameController size={18} className="mr-2" />
              <span>Connect</span>
            </button>
            <button className="block w-full text-left px-3 py-2 rounded-md hover:bg-purple-700 transition duration-200 flex items-center">
              <Plus size={18} className="mr-2" />
              <span>Create</span>
            </button>
            <button className="block w-full text-left px-3 py-2 rounded-md hover:bg-purple-700 transition duration-200 flex items-center">
              <Plus size={18} className="mr-2" />
              <span>Add</span>
            </button>
            <button className="block w-full text-left px-3 py-2 rounded-md hover:bg-purple-700 transition duration-200 flex items-center">
              <Users size={18} className="mr-2" />
              <span>Friends</span>
            </button>
            <button 
              onClick={() => {
                setIsProfileOpen(!isProfileOpen);
                setIsMenuOpen(false);
              }}
              className="block w-full text-left px-3 py-2 rounded-md hover:bg-purple-700 transition duration-200 flex items-center"
            >
              <img 
                src={currentUser.avatar} 
                alt="Profile" 
                className="w-6 h-6 rounded-full mr-2" 
              />
              <span>Profile</span>
            </button>
          </div>
        </div>
      )}

      {/* Profile dropdown */}
      {isProfileOpen && (
        <div className="absolute right-0 md:right-4 top-16 w-full md:w-80 bg-purple-800 shadow-xl rounded-b-lg md:rounded-lg overflow-hidden z-50">
          <UserProfile onClose={() => setIsProfileOpen(false)} />
        </div>
      )}
    </header>
  );
};

export default Navbar;